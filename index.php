<!DOCTYPE html>
<html>
<head>
    <title>Решение лабиринта</title>
    <style>
       .frame {
        border: 10px solid black;
       }
  </style>
</head>
<body>
    <header>
        <h1>Решатель Антон</h1>
    </header>
    
    <form method="post" action="">
        <label for="arr">Введите двумерный массив, вводите значения через запяту, каждую строку на новой строке:</label>
        <br>
        <textarea name="arr" id="arr" rows="10" cols="20"></textarea>
        <br>
        <label for="x1">Введите координату x1:</label>
        <input type="number" name="x1" id="x1">
        
        <label for="y1">Введите координату y1:</label>
        <input type="number" name="y1" id="y1">
        <br>
        <label for="x2">Введите координату x2:</label>
        <input type="number" name="x2" id="x2">
        
        <label for="y2">Введите координату y2:</label>
        <input type="number" name="y2" id="y2">
        <br>
        <input type="submit" value="Выполнить">
        <br>
    </form>

    <?php
    require_once("mazeUtils.php");
    require_once("maze.php");
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Получаем массив из POST-запроса
        $input = $_POST['arr'];
        if ($input!==''){
            $rows = explode("\n", $input);
            $output = array();
            foreach ($rows as $row) {
                $row = trim($row);
                $elements = explode(",", $row);
                $output_row = array();
                foreach ($elements as $element) {
                    $output_row[] = intval(trim($element));
                }
                $output[] = $output_row;
            } 
            $START=new Cell(intval($_POST['x1']),intval($_POST['y1']));
            $END=new Cell(intval($_POST['x2']),intval($_POST['y2']));
            if ($output[$START->x][$START->y]!==0 && $output[$END->x][$END->y]!==0 ){
                $maze=new Maze($output,$START,$END);
                $maze->solution();
                if ($maze->is_solved()){
                    $maze->get_way_img(100);
                    echo "<img src='solution.png' class='frame'>";
                    echo "<br>";
                    echo "Длина пути: ".$maze->get_length();
                }else{
                    echo "Нет ответа";
                }
            }else{
                    echo "Нет варианта";
            }
            }
        }
    ?>

</body>
</html>
