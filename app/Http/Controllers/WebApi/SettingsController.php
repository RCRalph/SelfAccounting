<?php

namespace App\Http\Controllers\WebApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Currency;
use App\Category;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getSettings()
    {
        $currencies = Currency::all();
        $categories = auth()->user()->categories->map(function($item) {
            return collect($item)->except("user_id")->except("created_at")->except("updated_at")->toArray();
        })->groupBy('currency_id');

        return response()->json(compact('currencies', 'categories'));
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
            $dataDirectory . "start_date" => ["present", "date", "nullable", "before_or_equal:" . $dataDirectory . "end_date"]
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
            $categoryWithTheSameName = $userCategories->where("currency_id", $category["currency_id"])->where("name", $category["name"]);
            $categoryId = 0;

            if ($category["id"] == null) {
                unset($category["id"]);

                if ($categoryWithTheSameName->count()) {
                    $categoryId = $categoryWithTheSameName->first()->id;
                    Category::findOrFail($categoryId)->update($category);
                    $userCategories = $userCategories->toArray();

                    foreach ($userCategories as $key => $val) {
                        if ($val["id"] == $categoryId) {
                            $userCategories[$key] = $category;
                            break;
                        }
                    }

                    $userCategories = collect($userCategories);
                }
                else {
                    $c = Category::create($category);
                    $categoryId = $c->id;
                    $userCategories = $userCategories->merge([array_merge(
                        $category,
                        ["id" => $categoryId]
                    )]);
                }
            }
            else {
                Category::findOrFail($category["id"])->update($category);
                $categoryId = $category["id"];

                $userCategories = $userCategories->toArray();

                foreach ($userCategories as $key => $val) {
                    if ($val["id"] == $categoryId) {
                        $userCategories[$key] = $category;
                        break;
                    }
                }

                $userCategories = collect($userCategories);
            }
        }

        return response()->json([
            "categories" => $userCategories->map(function($item) {
                return collect($item)->except("user_id")->except("created_at")->except("updated_at")->toArray();
            })->groupBy('currency_id'),
        ]);
    }
}
