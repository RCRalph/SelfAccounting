<?php

namespace App\Utils;

use App\Http\Controllers\WebAPI\ChartsController;
use Illuminate\Support\Collection;

class AccountBalanceHistory
{
    private array $data, $accounts, $colors, $datasetsConfig;

    private ?string $lastDate;

    public function __construct(Collection $accounts, string|null $lastDate)
    {
        $this->data = [];
        $this->lastDate = $lastDate;
        $this->accounts = [];

        $this->datasetsConfig = [
            "steppedLine" => true,
            "fill" => false,
            "borderWidth" => 5,
        ];

        $this->colors = ChartsController::getColors($accounts->count() + 1);
        foreach ($accounts as $i => $account) {
            $this->accounts[$account->id] = [
                "name" => $account->name,
                "color" => $this->colors[$i]
            ];
        }
    }

    private function addEntry(int $account, string $date, float $value): void
    {
        if (!array_key_exists($account, $this->data)) {
            $this->data[$account] = [[
                "x" => $date,
                "y" => round($value, 2)
            ]];
        } else if (end($this->data[$account])["x"] != $date) {
            $this->data[$account][] = [
                "x" => $date,
                "y" => round($value + end($this->data[$account])["y"], 2)
            ];
        } else {
            $this->data[$account][count($this->data[$account]) - 1]["y"] = round(
                $value + end($this->data[$account])["y"],
                2
            );
        }

        if (is_null($this->lastDate) || strtotime($this->lastDate) < strtotime($date)) {
            $this->lastDate = $date;
        }
    }

    public function addStartBalance(string $startDate, array $startBalance): void
    {
        foreach ($startBalance as $balance) {
            $this->addEntry($balance["account_id"], $startDate, $balance["balance"]);
            $this->addEntry(0, $startDate, $balance["balance"]);
        }
    }

    public function addEntries(Collection $data): void
    {
        foreach ($data as $entry) {
            $this->addEntry($entry->account_id, $entry->date, $entry->value);
        }
    }

    public function addSum(Collection $data): void
    {
        $this->accounts[0] = [
            "name" => "Sum",
            "color" => end($this->colors)
        ];

        foreach ($data as $entry) {
            $this->addEntry(0, $entry->date, $entry->value);
        }
    }

    public function getChartData(): array
    {
        $result = ["datasets" => []];

        if (!$this->data) return $result;
        
        foreach ($this->accounts as $id => $data) {
            if ($this->data[$id] && end($this->data[$id])["x"] != $this->lastDate) {
                $this->data[$id][] = [
                    "x" => $this->lastDate,
                    "y" => end($this->data[$id])["y"]
                ];
            }

            $result["datasets"][] = [
                ...$this->datasetsConfig,
                "label" => $data["name"],
                "data" => $this->data[$id],
                "borderColor" => $data["color"],
                "backgroundColor" => $data["color"],
                "stepped" => true
            ];
        }

        return $result;
    }
}
