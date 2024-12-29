<?php
if (isset($_GET['data']) || isset($_GET['questLine'])) {
    $needs = isset($_GET['data']) ? array_map('trim', explode(',', $_GET['data'])) : [];
    $questLine = isset($_GET['questLine']) ? strtolower(trim($_GET['questLine'])) : null;
    $questLevels = isset($_GET['questLevels']) && $_GET['questLevels'] !== '' ? array_map('trim', explode(',', $_GET['questLevels'])) : [];

    $datapoint = "https://raw.githubusercontent.com/septor/cherrytree/refs/heads/main/";
    $baseCampData = json_decode(file_get_contents($datapoint . "basecamp.json"), true);
    $questsData = json_decode(file_get_contents($datapoint . "quests.json"), true);

    $results = [];
    $totals = [];
    $expandedNeeds = [];
    $expandedQuestLevels = [];

    foreach ($needs as $need) {
        if (strpos($need, '-') !== false) {
            list($start, $end) = explode('-', $need);
            $expandedNeeds = array_merge($expandedNeeds, range(trim($start), trim($end)));
        } else {
            $expandedNeeds[] = trim($need);
        }
    }

    if (!empty($questLevels)) {
        foreach ($questLevels as $questLevel) {
            if (strpos($questLevel, '-') !== false) {
                list($start, $end) = explode('-', $questLevel);
                $expandedQuestLevels = array_merge($expandedQuestLevels, range(trim($start), trim($end)));
            } else {
                $expandedQuestLevels[] = trim($questLevel);
            }
        }
    }

    $expandedNeeds = array_unique($expandedNeeds);
    $expandedQuestLevels = array_unique($expandedQuestLevels);

    foreach ($expandedNeeds as $need) {
        if (isset($baseCampData[$need]['resources'])) {
            $results[$need] = $baseCampData[$need]['resources'];
            foreach ($baseCampData[$need]['resources'] as $key => $value) {
                if (is_numeric($value)) {
                    if (!isset($totals[$key])) {
                        $totals[$key] = 0;
                    }
                    $totals[$key] += $value;
                }
            }
        }
    }

    if ($questLine && isset($questsData[$questLine])) {
        if (empty($expandedQuestLevels)) {
            $expandedQuestLevels = array_keys($questsData[$questLine]);
        }

        foreach ($expandedQuestLevels as $level) {
            if (isset($questsData[$questLine][$level])) {
                foreach ($questsData[$questLine][$level] as $key => $value) {
                    if (is_numeric($value)) {
                        if (!isset($totals[$key])) {
                            $totals[$key] = 0;
                        }
                        $totals[$key] += $value;
                    }
                }
            }
        }
    }

    if (empty($results) && empty($totals)) {
        echo "No data found.";
    } else {
        foreach ($totals as $item => $total) {
            echo number_format($total) . "x " . $item . "<br>";
        }
    }
}