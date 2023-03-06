<?php

require_once "mazeUtils.php";

class Maze
{
    public $maze;
    public $START;
    public $END;
    public $correct_way;
    public $LENGTH;
    public $HEIGHT;
    public $paths;
    public $compass_map;
    public $way_map;
    public function __construct($maze, Cell $start, Cell $end)
    {
        $this->maze = $maze;
        $this->START = $start;
        $this->END = $end;
        $this->LENGTH = count($this->maze[0]);
        $this->HEIGHT = count($this->maze);
        $this->paths = [];
        for ($i = 0; $i < $this->HEIGHT; $i++) {
            $this->paths[$i] = [];
        }
        $this->paths[$this->START->x][$this->START->y] =
            $this->maze[$this->START->x][$this->START->y];
        $this->correct_way = $this->paths;
        $this->stack = new Stack($this->START, $this->START);
    }

    public function look($dirrection, $is_answer = 0)
    {
        $x = 0;
        $y = 0;
        if ($dirrection == "up") {
            $x = -1;
        } elseif ($dirrection == "right") {
            $y = 1;
        } elseif ($dirrection == "down") {
            $x = 1;
        } elseif ($dirrection == "left") {
            $y = -1;
        }
        $current = $this->stack->curr;
        $way_x = $current->x + $x;
        $way_y = $current->y + $y;
        $way = new Cell($way_x, $way_y);

        if (
            -1 < $way_x &&
            $way_x < $this->HEIGHT &&
            (-1 < $way_y && $way_y < $this->LENGTH) &&
            $way != $this->stack->last
        ) {
            if ($this->maze[$way_x][$way_y] != 0) {
                if (array_key_exists($way_y, $this->paths[$way_x])) {
                    if (
                        $this->paths[$way_x][$way_y] >
                        $this->paths[$current->x][$current->y] +
                            $this->maze[$way_x][$way_y]
                    ) {
                        $this->paths[$way_x][$way_y] =
                            $this->paths[$current->x][$current->y] +
                            $this->maze[$way_x][$way_y];
                        return $way;
                    }
                } else {
                    $this->paths[$way_x][$way_y] =
                        $this->paths[$current->x][$current->y] +
                        $this->maze[$way_x][$way_y];
                    return $way;
                }
            }
        }
        return false;
    }

    public function observe()
    {
        $vars = [];
        $up = $this->look("up");
        $right = $this->look("right");
        $down = $this->look("down");
        $left = $this->look("left");

        if ($up) {
            array_push($vars, $up);
        }
        if ($right) {
            array_push($vars, $right);
        }
        if ($down) {
            array_push($vars, $down);
        }
        if ($left) {
            array_push($vars, $left);
        }
        return $vars;
    }
    public function get_length()
    {
        $label = $this->END;
        return $this->correct_way[$label->x][$label->y];
    }
    public function pathfind()
    {
        while ($this->stack->getLength() !== 0) {
            $vars = $this->observe();
            if (count($vars) === 0) {
                $this->stack->remove();
            } elseif (count($vars) === 1) {
                $this->stack->moveCell($vars[0]);
            } else {
                $current = $this->stack->curr;
                foreach ($vars as $new) {
                    $this->stack->add($new, $current);
                }
            }
        }
    }

    public function solution()
    {
        $this->pathfind();
        if ($this->is_solved()) {
            $label = new Cell($this->END->x, $this->END->y);
            $this->correct_way[$label->x][$label->y] =
                $this->paths[$label->x][$label->y];
            $min = $this->paths[$label->x][$label->y];

            while ($label != $this->START) {
                $x = 0;
                $y = 0;
                $dirrection="";
                if (
                    $label->x + 1 < $this->HEIGHT &&
                    isset($this->paths[$label->x + 1][$label->y])
                ) {
                    if ($this->paths[$label->x + 1][$label->y] < $min) {
                        $min = $this->paths[$label->x + 1][$label->y];
                        $x = 1;
                        $y = 0;
                        $dirrection="u";
                    }
                }
                if (
                    $label->x - 1 > -1 &&
                    isset($this->paths[$label->x - 1][$label->y])
                ) {
                    if ($this->paths[$label->x - 1][$label->y] < $min) {
                        $min = $this->paths[$label->x - 1][$label->y];
                        $x = -1;
                        $y = 0;
                        $dirrection="d";
                    }
                }
                if (
                    $label->y + 1 < $this->LENGTH &&
                    isset($this->paths[$label->x][$label->y + 1])
                ) {
                    if ($this->paths[$label->x][$label->y + 1] < $min) {
                        $min = $this->paths[$label->x][$label->y + 1];
                        $y = 1;
                        $x = 0;
                        $dirrection="l";
                    }
                }
                if (
                    $label->y - 1 > -1 &&
                    isset($this->paths[$label->x][$label->y - 1])
                ) {
                    if ($this->paths[$label->x][$label->y - 1] < $min) {
                        $min = $this->paths[$label->x][$label->y - 1];
                        $y = -1;
                        $x = 0;
                        $dirrection="r";
                    }
                }
                $label->x += $x;
                $label->y += $y;
                $this->compass_map=$dirrection.$this->compass_map;
                $this->correct_way[$label->x][$label->y] =
                    $this->paths[$label->x][$label->y];
                //array_push($this->correct_way, new Cell($label->x, $label->y));
            }
        }
    }
    public function get_correct_way()
    {
        $corr = $this->correct_way;
        $output = [];
        for ($i = 0; $i < count($corr); $i++) {
            $output[$i][0] = $corr[$i]->x;
            $output[$i][1] = $corr[$i]->y;
        }
        return $output;
    }
    public function is_solved()
    {
        if ($this->paths[$this->END->x][$this->END->y]) {
            return true;
        } else {
            return false;
        }
    }
    public function get_way_img($scale = 8)
    {
        $image = imagecreatetruecolor(
            $this->LENGTH * $scale,
            $this->HEIGHT * $scale
        );

        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $green = imagecolorallocate($image, 0, 255, 0);

        imagefill($image, 0, 0, $black);

        for ($i = 0; $i < $this->LENGTH; $i++) {
            for ($j = 0; $j < $this->HEIGHT; $j++) {
                if ($this->maze[$j][$i] !== 0) {
                    if ($this->correct_way[$j][$i]) {
                        $color = $green;
                    } else {
                        $color = $white;
                    }
                    for ($x = $i * $scale; $x < $i * $scale + $scale; $x++) {
                        for (
                            $y = $j * $scale;
                            $y < $j * $scale + $scale;
                            $y++
                        ) {
                            imagesetpixel($image, $x, $y, $color);
                        }
                    }
                }
            }
        }
        imagepng($image, "img/solution.png");
        imagedestroy($image);
    }
}

?>
