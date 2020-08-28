<?php

namespace App\Http\Controllers\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Currency;
use App\Category;
use App\MeanOfPayment;
use App\Income;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function getSettings()
    {
        // Get currencies
        $currencies = Currency::all();

        // Get categories
        $categories = auth()->user()->categories->map(function($item) {
            unset($item["user_id"]);
            unset($item["created_at"]);
            unset($item["updated_at"]);
            return $item;
        })->groupBy("currency_id");

        // Get means of payment
        $means = auth()->user()->meansOfPayment->map(function($item) {
            unset($item["user_id"]);
            unset($item["created_at"]);
            unset($item["updated_at"]);
            return $item;
        })->groupBy("currency_id");

        return response()->json(compact("currencies", "categories", "means"));
    }

    public function saveCategories()
    {
        // Validate
        $dataDirectory = "categories.*.*.";
        $data = request()->validate([
            $dataDirectory . "count_to_summary" => ["required", "boolean"],
            $dataDirectory . "currency_id" => ["required", "exists:currencies,id"],
            $dataDirectory . "end_date" => ["present", "date", "nullable", "after_or_equal:" . $dataDirectory . "start_date"],
            $dataDirectory . "id" => ["present", "integer", "min:0", "not_in:0", "nullable"],
            $dataDirectory . "income_category" => ["required", "boolean"],
            $dataDirectory . "name" => ["required", "string", "max:32"],
            $dataDirectory . "outcome_category" => ["required", "boolean"],
            $dataDirectory . "show_on_charts" => ["required", "boolean"],
            $dataDirectory . "start_date" => ["present", "date", "nullable"]
        ]);

        if (count($data) == 0) {
            Category::where("user_id", auth()->user()->id)->delete();
            return response()->json([
                "categories" => []
            ]);
        }

        // Collect into one-dimentional array
        $categories = [];
        $idsInData = [];
        foreach ($data["categories"] as $currency) {
            foreach ($currency as $category) {
                $category["user_id"] = auth()->user()->id;
                array_push($categories, $category);
                if ($category["id"] != null) {
                    array_push($idsInData, $category["id"]);
                }
            }
        }

        // Get IDs from the database
        $userCategories = collect(auth()->user()->categories);
        $idsInDB = [];
        foreach ($userCategories as $category) {
            array_push($idsInDB, $category["id"]);
        }

        // Find IDs to delete and then delete them
        $toDelete = [];
        foreach ($idsInDB as $id) {
            if (array_search($id, $idsInData) === false) {
                array_push($toDelete, $id);
            }
        }

        if (count($toDelete)) {
            Category::destroy($toDelete);
            $userCategories = $userCategories->whereNotIn("id", $toDelete);
        }

        // Enter into the database
        foreach ($categories as $category) {
            if ($category["id"] == null) {
				unset($category["id"]);
				$c = Category::create($category);
				$userCategories = $userCategories->merge([array_merge(
					$category,
					["id" => $c->id]
				)]);
            }
            else {
                Category::findOrFail($category["id"])->update($category);
                $userCategories = $userCategories->toArray();

                foreach ($userCategories as $key => $val) {
                    if ($val["id"] == $category["id"]) {
                        $userCategories[$key] = $category;
                        break;
                    }
                }

                $userCategories = collect($userCategories);
            }
        }

        return response()->json([
            "categories" => $userCategories->map(function($item) {
                unset($item["user_id"]);
                unset($item["created_at"]);
                unset($item["updated_at"]);
                return $item;
            })->groupBy("currency_id"),
        ]);
    }

    public function saveMeans()
    {
        // Validate
        $dataDirectory = "means.*.*.";
        $data = request()->validate([
            $dataDirectory . "count_to_summary" => ["required", "boolean"],
            $dataDirectory . "currency_id" => ["required", "exists:currencies,id"],
            $dataDirectory . "first_entry_amount" => ["required", "numeric"],
            $dataDirectory . "first_entry_date" => ["required", "date"], // Validate income things, u know
            $dataDirectory . "id" => ["present", "integer", "min:0", "not_in:0", "nullable"],
            $dataDirectory . "income_mean" => ["required", "boolean"],
            $dataDirectory . "name" => ["required", "string", "max:32"],
            $dataDirectory . "outcome_mean" => ["required", "boolean"],
            $dataDirectory . "show_on_charts" => ["required", "boolean"]
        ]);

        if (count($data) == 0) {
            MeanOfPayment::where("user_id", auth()->user()->id)->delete();
            return response()->json([
                "means" => []
            ]);
        }

        // Collect into one-dimentional array
        $means = [];
        $idsInData = [];
        foreach ($data["means"] as $currency) {
            foreach ($currency as $mean) {
                $mean["user_id"] = auth()->user()->id;
                array_push($means, $mean);
                if ($mean["id"] != null) {
                    array_push($idsInData, $mean["id"]);
                }
            }
        }

        // Get IDs from the database
        $userMeans = collect(auth()->user()->meansOfPayment);
        $idsInDB = [];
        foreach ($userMeans as $mean) {
            array_push($idsInDB, $mean["id"]);
        }

        $toDelete = [];
        foreach ($idsInDB as $id) {
            if (array_search($id, $idsInData) === false) {
                array_push($toDelete, $id);
            }
        }

        if (count($toDelete)) {
            MeanOfPayment::destroy($toDelete);
            $userMeans = $userMeans->whereNotIn("id", $toDelete);
        }

        // Enter into the database
        foreach ($means as $mean) {
            if ($mean["id"] == null) {
                unset($mean["id"]);
                $m = MeanOfPayment::create($mean);
                $userMeans = $userMeans->merge([array_merge(
                    $mean,
                    ["id" => $m->id]
                )]);
            }
            else {
                MeanOfPayment::findOrFail($mean["id"])->update($mean);
                $userMeans = $userMeans->toArray();

                foreach ($userMeans as $key => $val) {
                    if ($val["id"] == $mean["id"]) {
                        $userMeans[$key] = $mean;
                        break;
                    }
                }

                $userMeans = collect($userMeans);
            }
        }

        return response()->json([
            "means" => $userMeans->map(function($item) {
                unset($item["user_id"]);
                unset($item["created_at"]);
                unset($item["updated_at"]);
                return $item;
            })->groupBy("currency_id")
        ]);
    }
}
