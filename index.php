<!DOCTYPE html>
<html data-bs-theme="dark">
    <head>
        <title>Решение лабиринта</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-gradient">
                <a class="navbar-brand ml-3" href="#">
                    <h1>Решатель Антон v1.1</h1>
                </a>
            </nav>
        </header>
        <div class="container-fluid">
            <div class="row p-3">
                <div class="col-md-4 p-3 content ">
                    <form method="post" action="">
                        <label class="form-label" for="arr">
                            <h4>Введите двумерный массив, элементы можно не разделять, или разделять следующими знаками:<br>"(" "[" "," "пробел". <br>
                            Строки необходимо разделять с помощью следующих символов: <br>
                            "]" ")" "\n" - перенос строки. </h4>
                        </label>
                        <br>
                        <textarea class="form-control" name="arr" id="arr" rows="10" cols="20"></textarea>
                        <br>
                        <label class="form-label" for="y1">
                            <p class="text-justify">Введите координату X Начальной точки:</p>
                        </label>
                        <input type="text" class="form-control" name="y1" id="y1">
                        <br>
                        <label class="form-label" for="x1">
                            <p class="text-justify">Введите координату Y Начальной точки:</p>
                        </label>
                        <input type="text" class="form-control" name="x1" id="x1">
                        <br>
                        <label class="form-label" for="y2">
                            <p class="text-justify">Введите координату X Конечной точки:</p>
                        </label>
                        <input type="text" class="form-control" name="y2" id="y2">
                        <br>
                        <label class="form-label" for="x2">
                            <p class="text-justify">Введите координату Y Конечной точки:</p>
                        </label>
                        <input type="text" class="form-control" name="x2" id="x2">
                        <br>
                        <input type="submit" class="form-control" value="Выполнить">
                        <br>
                    </form>
                    <p>Большую часть работы занимает генерация картинки,при особо большом лабиринте возможно долгое ожидание  </p>
                    <p>
                        Сложность данного алгоритма - ~O(n*n), - просмотреть каждую ячейку.
                        Короче сделать невозможно по моему мнению, ведь не просмотрев все пути, невозможно утверждать что кратчайший путь найден.

                        Суть моего алгоритма в том, что он считает кратчайший путь в каждую развилку, если находит уже просчитанную развилку и видит, что может короче - считает из неё заново.
                    </p>
                </div>
                <div class="col-md-2"></div>
                <div class=" col-md-6 p-3 content"> <?php
                    require_once "mazeUtils.php";
                    require_once "maze.php";
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        $input = $_POST["arr"];
                        if ($input !== "") {
                            $input=str_replace("(",'',$input);
                            $input=str_replace("(",'',$input);
                            $input=str_replace("[",'',$input);
                            $input=str_replace(",",'',$input);
                            $input=str_replace(" ",'',$input);
                            if( strpos($input,"\n")){
                                $input=str_replace(")",'',$input);
                                $input=str_replace("]",'',$input);
                                $rows = explode("\n", $input);
                            }elseif(strpos($input,"]")){
                                $input=str_replace(")",'',$input);
                                $rows = explode("]", $input);
                            }elseif(strpos($input,")")){
                                $input=str_replace("]",'',$input);
                                $rows = explode(")", $input);
                            }
                            $output = [];

                            foreach ($rows as $row) {
                                $row = trim($row);
                                if ($row !== "") {
                                    $output_row = [];
                                    $elements=str_split($row);
                                    foreach ($elements as $element) {
                                        if ($element !== "") {
                                            $output_row[] = intval(trim($element));
                                        }
                                    }
                                    $output[] = $output_row;
                                }
                            }
                            $START = new Cell(intval($_POST["x1"]), intval($_POST["y1"]));
                            $END = new Cell(intval($_POST["x2"]), intval($_POST["y2"]));
                            if (
                                $output[$START->x][$START->y] !== 0 &&
                                $output[$END->x][$END->y] !== 0
                            ) {
                                $maze = new Maze($output, $START, $END);
                                $maze->solution();
                                if ($maze->is_solved()) {
                                    $maze->get_way_img(128);
                                    echo "<h4 class='text-center'>Длина пути: " .$maze->get_length() ."</h4>";
                                    echo "<h6>Ответ вида r-вправо,d-вниз,l-влево,u-вверх :</h6><h6>[".$maze->compass_map."]</h6>";
                                    echo "<img src='img/solution.png' class='mx-auto d-block img-fluid frame'><br>";
                                } else {
                                    echo "<h4>Нет ответа</h4>";
                                }
                            } else {
                                echo "<h4>Нет ответа</h4>";
                            }
                        }
                    }
                ?> </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    </body>
</html>
