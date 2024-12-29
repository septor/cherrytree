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
    $rewardTotals = [];
    $highestLevels = [];

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
        if (isset($baseCampData[$need])) {
            $levelData = $baseCampData[$need];
            if (isset($levelData['resources'])) {
                foreach ($levelData['resources'] as $key => $value) {
                    if (!isset($totals[$key])) {
                        $totals[$key] = 0;
                    }
                    $totals[$key] += $value;
                }
            }
            if (isset($levelData['requirements'])) {
                foreach ($levelData['requirements'] as $skill => $level) {
                    if (!isset($highestLevels[$skill]) || $level > $highestLevels[$skill]) {
                        $highestLevels[$skill] = $level;
                    }
                }
            }
            if (isset($levelData['rewards'])) {
                foreach ($levelData['rewards'] as $reward => $amount) {
                    if (!isset($rewardTotals[$reward])) {
                        $rewardTotals[$reward] = 0;
                    }
                    $rewardTotals[$reward] += $amount;
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
                    if (!isset($totals[$key])) {
                        $totals[$key] = 0;
                    }
                    $totals[$key] += $value;
                }
            }
        }
    }

    if (empty($totals) && empty($rewardTotals) && empty($highestLevels)) {
        echo "No data found.";
    } else {
        echo "<table style='border-collapse: collapse; width: 100%; text-align: left;'>
        <tr>
        <th>Required Resources</th>
        <th>Highest Required Levels</th>
        <th>Total Rewards</th>
        </tr>
        <tr>
        <td style='vertical-align: top;'>";
        foreach ($totals as $item => $total) {
            echo number_format($total) . "x " . $item . "<br>";
        }
        echo "</td>
        <td style='vertical-align: top;'>";
        if(!empty($highestLevels)) {
            foreach ($highestLevels as $skill => $level) {
                echo "Level ".$level." ".$skill."<br>";
            }
        } else {
            echo "No requirements needed.";
        }
        echo "</td>
        <td style='vertical-align: top;'>";
        if (!empty($rewardTotals)) {
            foreach ($rewardTotals as $reward => $total) {
                echo $total . "x " . $reward . "<br>";
            }
        } else {
            echo "No rewards found.";
        }
        echo "</td>
        </tr>
        </table>";
    }
}