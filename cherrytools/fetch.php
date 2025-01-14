<?php
function number_format_short($n) {
	if ($n < 900) {
		$n_format = number_format($n, 2);
		$suffix = '';
	} else if ($n < 900000) {
		$n_format = number_format($n / 1000, 2);
		$suffix = 'k';
	} else if ($n < 900000000) {
		$n_format = number_format($n / 1000000, 2);
		$suffix = 'm';
	} else if ($n < 900000000000) {
		$n_format = number_format($n / 1000000000, 2);
		$suffix = 'b';
	} else {
		$n_format = number_format($n / 1000000000000, 2);
		$suffix = 't';
	}
	return $n_format . $suffix;
}

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
                $questLevelData = $questsData[$questLine][$level];
                if (isset($questLevelData['resources'])) {
                    foreach ($questLevelData['resources'] as $key => $value) {
                        if (!isset($totals[$key])) {
                            $totals[$key] = 0;
                        }
                        $totals[$key] += $value;
                    }
                }
                if (isset($questLevelData['requirements'])) {
                    foreach ($questLevelData['requirements'] as $skill => $level) {
                        if (!isset($highestLevels[$skill]) || $level > $highestLevels[$skill]) {
                            $highestLevels[$skill] = $level;
                        }
                    }
                }
                if (isset($questLevelData['rewards'])) {
                    foreach ($questLevelData['rewards'] as $reward => $amount) {
                        if (!isset($rewardTotals[$reward])) {
                            $rewardTotals[$reward] = 0;
                        }
                        $rewardTotals[$reward] += $amount;
                    }
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
        <th>Required Levels</th>
        <th>Total Rewards</th>
        </tr>
        <tr>
        <td style='vertical-align: top;'>";
        foreach ($totals as $item => $total) {
            echo number_format_short($total) . " " . $item . "<br>";
        }
        echo "</td>
        <td style='vertical-align: top;'>";
        if (!empty($highestLevels)) {
            foreach ($highestLevels as $skill => $level) {
                echo $level . " " . $skill . "<br>";
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