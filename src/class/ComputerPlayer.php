<?php

require_once dirname(__DIR__) . "/constants.php";

/**
 * Player
 */
class ComputerPlayer extends Player{

    public function isHuman() {
        return false;
    }

    /**
     * @return integer $cell return the value from 1 to 9 for the next move
     */
    public function computerMove($table, $symbol) {

        // can I win at this move? If yes return the move
        $move = $this->winningMove($table, $symbol);
        if ($move !== null) {
            return $move;
        }

        // can the player win at his next move? If he can stop his move
        $move = $this->stopNextWinnerMove($table, $symbol);
        if ($move !== null) {
            return $move;
        }

        // take one corner if they are all free
        $move = $this->takeCorner($table, $symbol);
        if ($move) {
            return $move;
        }

        // take the center if is free
        $move = $this->takeCenter($table, $symbol);
        if ($move) {
            return $move;
        }

        // take whatever is available
        $move = $this->whateverAvailable($table, $symbol);
        if ($move) {
            return $move;
        }

    }

    private function isTableEmpty($table) {
        for ($i=0; $i<3; $i++) {
            for ($j=0; $j<3; $j++) {
                if ($table[$i][$j] !== _) {
                    return false;
                }
            }
        }
        return true;
    }

    public function winningMove($table, $symbol) {

        // horizontal
        for ($i=0; $i<3; $i++) {
            $count = 0;
            for ($j=0; $j<3; $j++) {
                if ($table[$i][$j] === $symbol) {
                    $count++;
                } elseif ($table[$i][$j] === _) {
                    $position = $this->getPosition($i,$j);
                } else {
                    $count = 0;
                    break;
                }
            }
            if ($count == 2) {
                return $position;
            }
        }

        // vertical
        for ($i=0; $i<3; $i++) {
            $count = 0;
            for ($j=0; $j<3; $j++) {
                if ($table[$j][$i] === $symbol) {
                    $count++;
                } elseif ($table[$j][$i] === _) {
                    $position = $this->getPosition($j,$i);
                } else {
                    $count = 0;
                    break;
                }
            }
            if ($count == 2) {
                return $position;
            }
        }

        // main diagonal
        $count = 0;
        for ($i=0; $i<3; $i++) {
            if ($table[$i][$i] === $symbol) {
                $count++;
            } elseif ($table[$i][$i] === _) {
                $position = $this->getPosition($i,$i);
            } else {
                $count = 0;
                break;
            }
        }
        if ($count == 2) {
            return $position;
        }

        // other diagonal
        $count = 0;
        for ($i=0; $i<3; $i++) {
            if ($table[$i][2-$i] === $symbol) {
                $count++;
            } elseif ($table[$i][2-$i] === _) {
                $position = $this->getPosition($i,2-$i);
            } else {
                $count = 0;
                break;
            }
        }
        if ($count == 2) {
            return $position;
        }

    }

    public function stopNextWinnerMove($table, $symbol) {
        $other_player_symbol = $symbol === x ? o : x;
        return $this->winningMove($table, $other_player_symbol);
    }

    public function takeCorner($table, $symbol) {

        $corners = [
            $table[0][0],
            $table[0][2],
            $table[2][0],
            $table[2][2]
        ];

        $counter = 0;
        foreach ($corners as $corner) {
            if ($corner === _) {
                $counter ++;
            }
        }

        // all corner are available take top left
        if ($counter == 4) {
            return 1;
        }

        // only 3 corner cells are available, position in the opposite of the current corner cell occupied
        if ($counter == 3) {
            $corner_number = 1;
            foreach ($corners as $key => $corner) {
                if ($corner !== _) {
                    $corner_number = $key;
                }
            }

            switch($corner_number) {
                case 0: return 9;
                case 1: return 7;
                case 2: return 3;
                case 3: return 1;
            }
        }

        // if there's one or more free corner and the center is not taken, take a corner
        if ($counter >= 1 and $table[1][1]) {
            $corner_number = 1;
            foreach ($corners as $key => $corner) {
                if ($corner === _) {
                    echo $key;
                    switch($key) {
                        case 0: return 1;
                        case 1: return 3;
                        case 2: return 7;
                        case 3: return 9;
                    }
                }
            }

        }

    }

    function takeCenter($table, $symbol) {
        if ($table[1][1] === _) {
            return 5;
        }
    }

    function whateverAvailable($table, $symbol) {
        for ($i=0; $i<3; $i++) {
            for ($j=0; $j<3; $j++) {
                if ($table[$i][$j] === _) {
                    return $this->getPosition($i, $j);
                }
            }
        }
    }

    private function getPosition($i, $j) {
        return $i*3 + $j%3 + 1;
    }

}
