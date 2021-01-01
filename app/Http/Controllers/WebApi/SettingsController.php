<?php

namespace App\Http\Controllers\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Currency;
use App\Category;
use App\MeanOfPayment;
use App\Income;
use App\Outcome;

use App\Rules\CorrectDateMeans;
use App\Rules\CorrectCategoryMeanID;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    private function getMeanDateLimits($sortedIO, $means) {
        foreach ($means as $key => $mean) {
            $lastIO = $sortedIO->where("mean_id", $mean["id"])->last();
            $means[$key]["date_limit"] = $lastIO != null ?
                $lastIO->date : null;
        }

        return $means;
    }

    private function saveCatsMeans($data, $type)
    {
        $TYPES = ["category", "mean"];
        if (!in_array($type, $TYPES)) {
            abort(500);
        }

        if (!isset($data["data"])) {
            ($type == $TYPES[0] ?
                Category::where("user_id", auth()->user()->id) :
                MeanOfPayment::where("user_id", auth()->user()->id)
            )->delete();

            Income::where("user_id", auth()->user()->id)
                ->update([$type . "_id" => null]);
            Outcome::where("user_id", auth()->user()->id)
                ->update([$type . "_id" => null]);

            return response()->json([
                "data" => []
            ]);
        }

        $data = $data["data"];

        // Gather into one-dimentional array
        $entries = [];
        $idsInData = [];
        foreach ($data as $currency) {
            foreach ($currency as $entry) {
                $entry["user_id"] = auth()->user()->id;
                array_push($entries, $entry);

                if ($entry["id"] != 0) {
                    array_push($idsInData, $entry["id"]);
                }
            }
        }

        // Get IDs from the database
        $entriesInDB = collect(
            $type == $TYPES[0] ?
            auth()->user()->categories :
            auth()->user()->meansOfPayment
        );

        $IDsInDB = [];
        foreach ($entriesInDB as $entry) {
            array_push($IDsInDB, $entry["id"]);
        }

        // Find IDs to delete
        $toDelete = [];
        foreach ($IDsInDB as $id) {
            if (!in_array($id, $idsInData)) {
                array_push($toDelete, $id);
            }
        }

        // Delete entries
        if ($toDelete) {
            if ($type == $TYPES[0]) {
                Category::destroy($toDelete);
            }
            else {
                MeanOfPayment::destroy($toDelete);
            }

            Income::whereIn($type . "_id", $toDelete)
                ->update([$type . "_id" => null]);
            Outcome::whereIn($type . "_id", $toDelete)
                ->update([$type . "_id" => null]);

            $entriesInDB = $entriesInDB->whereNotIn("id", $toDelete);
        }

        // Enter into the database
        $entriesInDB = $entriesInDB->toArray();

        foreach ($entries as $entry) {
            if (!$entry["id"]) {
                unset($entry["id"]);
                $created = [];

                if ($type == $TYPES[0]) {
                    $created = Category::create($entry);
                }
                else {
                    $created = MeanOfPayment::create($entry);
                }

                array_push($entriesInDB, $created->toArray());
            }
            else {
                if ($type == $TYPES[0]) {
                    Category::findOrFail($entry["id"])->update($entry);
                }
                else {
                    MeanOfPayment::findOrFail($entry["id"])->update($entry);
                }

                foreach ($entriesInDB as $key => $val) {
                    if ($val["id"] == $entry["id"]) {
                        $entriesInDB[$key] = $entry;
                        break;
                    }
                }
            }
        }

        $entriesInDB = array_map(
            function($item) {
                unset($item["user_id"], $item["created_at"], $item["updated_at"]);
                return $item;
            },
            $entriesInDB
        );

        $data = $type == $TYPES[0] ?
            $entriesInDB :
            $this->getMeanDateLimits(
                auth()->user()->income
                    ->concat(auth()->user()->outcome)
                    ->sortBy("date"),
                $entriesInDB
            );

        return collect($data)->groupBy("currency_id");
    }

    public function darkmode() // Change darkmode
    {
        $darkmode = request()->validate([
            "darkmode" => ["required", "boolean"]
        ])["darkmode"];

        auth()->user()->update(compact("darkmode"));

        return response("", 200);
    }

    public function getSettings()
    {
        // Get all income and outcome
        $incomeOutcome = auth()->user()->income
            ->concat(auth()->user()->outcome)
            ->sortBy("date");

        // Get currencies
        $currencies = Currency::all()
			->map(fn ($item) => $item->only("id", "ISO"));

        // Get categories
        $categories = auth()->user()->categories
            ->map(fn ($item) => collect($item)->forget("user_id", "created_at", "updated_at"))
            ->groupBy("currency_id")
            ->toArray();

        // Get means of payment
        $means = auth()->user()->meansOfPayment
            ->map(fn ($item) => collect($item)->forget("user_id", "created_at", "updated_at"))
            ->toArray();

        $means = collect($this->getMeanDateLimits($incomeOutcome, $means))
            ->groupBy("currency_id");

        // Set empty arrays to currencies with no categories / means
        foreach ($currencies as $currency) {
            if (!isset($categories[$currency["id"]])) {
                $categories[$currency["id"]] = [];
            }

            if (!isset($means[$currency["id"]])) {
                $means[$currency["id"]] = [];
            }
        }

        // Get last currency
        $lastIncomeOutcome = $incomeOutcome->last();
		$lastCurrency = $lastIncomeOutcome != null ?
            $lastIncomeOutcome->currency_id : 1;

        return response()->json(compact("currencies", "categories", "means", "lastCurrency"));
    }

    public function saveCategories()
    {
        // Validate
        $dataDirectory = "data.*.*";
        $data = request()->validate([
            "$dataDirectory.id" => ["required", "integer", new CorrectCategoryMeanID("category")],
            "$dataDirectory.currency_id" => ["required", "integer", "exists:currencies,id"],
            "$dataDirectory.name" => ["required", "string", "max:32"],
            "$dataDirectory.income_category" => ["required", "boolean"],
            "$dataDirectory.outcome_category" => ["required", "boolean"],
            "$dataDirectory.count_to_summary" => ["required", "boolean"],
            "$dataDirectory.start_date" => ["present", "date", "nullable"],
            "$dataDirectory.end_date" => ["present", "date", "nullable", "after_or_equal:$dataDirectory.start_date"]
        ]);

        $data = $this->saveCatsMeans($data, "category");
        return response()->json(compact("data"));
    }

    public function saveMeans()
    {
        // Validate
        $dataDirectory = "data.*.*";
        $data = request()->validate([
            "$dataDirectory.id" => ["required", "integer", new CorrectCategoryMeanID("mean")],
            "$dataDirectory.currency_id" => ["required", "integer", "exists:currencies,id"],
            "$dataDirectory.name" => ["required", "string", "max:32"],
            "$dataDirectory.income_mean" => ["required", "boolean"],
            "$dataDirectory.outcome_mean" => ["required", "boolean"],
            "$dataDirectory.count_to_summary" => ["required", "boolean"],
            "$dataDirectory.first_entry_date" => ["required", "date", new CorrectDateMeans],
            "$dataDirectory.first_entry_amount" => ["required", "numeric", "max:1e11", "min:-1e11", "not_in:-1e11,1e11"]
        ]);

        $data = $this->saveCatsMeans($data, "mean");
        return response()->json(compact("data"));
    }
}
