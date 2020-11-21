<?php

namespace App\Labyrinth;

/**
 * Class Labyrinth
 * This class aims to solve a labyrinth using Backtracking method.
 * @package App\Labyrinth
 */
class Labyrinth
{
    /**
     * The file name.
     *
     * @var string
     */
    protected $fileName;

    /**
     * The number of rows.
     *
     * @var int
     */
    protected $rows;

    /**
     * The number of columns.
     *
     * @var int
     */
    protected $columns;

    /**
     * The initial position X.
     *
     * @var int
     */
    protected $initPositionX;

    /**
     * The initial position Y.
     *
     * @var int
     */
    protected $initPositionY;

    /**
     * The final position X.
     *
     * @var int
     */
    protected $finalPositionX;

    /**
     * The final position Y.
     *
     * @var int
     */
    protected $finalPositionY;

    /**
     * The matrix used for the labyrinth.
     *
     * @var array
     */
    protected $matrix = [];

    /**
     * The number of possible directions.
     *
     * @var int
     */
    const DIRECTIONS = 4;

    /**
     * The North, East, South, West direction of rows.
     *
     * @var int
     */
    const DIRECTION_ROWS = [-1, 0, 1, 0];

    /**
     * The North, East, South, West direction of columns.
     *
     * @var int
     */
    const DIRECTION_COLUMNS = [0, 1, 0, -1];

    /**
     * The first line of the file.
     *
     * @var int
     */
    const FIRST_LINE = 1;

    /**
     * The second line of the file.
     *
     * @var int
     */
    const SECOND_LINE = 2;

    /**
     * The third line of the file.
     *
     * @var int
     */
    const THIRD_LINE = 3;

    /**
     * Labyrinth constructor.
     *
     * @param string $fileName The file name
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Read and load the data from an input file.
     *
     * @return void
     */
    private function _read(): void
    {
        $handle = fopen($this->fileName, 'r+');
        $lineNumber = 1;
        while ($line = fgets($handle)) {
            $line = trim($line);
            $data = explode(' ', $line);
            if ($lineNumber === static::FIRST_LINE) {
                $this->rows = current($data);
                $this->columns = next($data);
            } elseif ($lineNumber === static::SECOND_LINE) {
                $this->initPositionX = current($data);
                $this->initPositionY = next($data);
            } elseif ($lineNumber === static::THIRD_LINE) {
                $this->finalPositionX = current($data);
                $this->finalPositionY = next($data);
            } else {
                $this->matrix[] = $data;
            }
            $lineNumber++;
        }
    }

    /**
     * Check if the given coordinates are a possible solution.
     *
     * @param int $row The row index
     * @param int $column The column index
     * @return bool
     */
    protected function isOK(int $row, int $column): bool
    {
        if ($row > $this->rows || $row < 1) {
            return false;
        }
        if ($column > $this->columns || $column < 1) {
            return false;
        }
        if ($this->matrix[$row][$column] == 1 || ($row == $this->finalPositionX && $column == $this->finalPositionY)) {
            return true;
        }
        if ($this->matrix[$row][$column] == 0 || $this->matrix[$row][$column]) {
            return false;
        }

        return true;
    }

    /**
     * SHow the matrix which includes the solution of the labyrinth.
     *
     * @return void
     */
    private function _show(): void
    {
        foreach (range(0, $this->rows - 1) as $row) {
            foreach (range(0, $this->columns - 1) as $column) {
                print $this->matrix[$row][$column] . ' ';
            }
            print PHP_EOL;
        }
        print PHP_EOL;
    }

    /**
     * Show the coordinates based on the traversed path.
     *
     * @return void
     */
    private function _showCoordinates(): void
    {
        foreach (range(0, $this->rows - 1) as $row) {
            foreach (range(0, $this->columns - 1) as $column) {
                if ($this->matrix[$row][$column] > 1) {
                    print $row . ' - ' . $column . PHP_EOL;
                }
            }
        }
        print PHP_EOL;
    }

    /**
     * Recursively solve the labyrinth by checking the neighbours in the North, East, South, West direction.
     *
     * @param int $row The row index
     * @param int $column The column index
     * @param int $step The step used to mark the traversed path
     * @return void
     */
    protected function solveLabyrinth(int $row, int $column, int $step): void
    {
        if ($row == $this->finalPositionX && $column == $this->finalPositionY) {
            $this->_show();
            $this->_showCoordinates();
        } else {
            foreach (range(0, static::DIRECTIONS - 1) as $directionIndex) {
                $rowNeighbour = $row + static::DIRECTION_ROWS[$directionIndex];
                $columnNeighbour = $column + static::DIRECTION_COLUMNS[$directionIndex];
                if ($this->isOK($rowNeighbour, $columnNeighbour)) {
                    $this->matrix[$rowNeighbour][$columnNeighbour] = $step;
                    $this->solveLabyrinth($rowNeighbour, $columnNeighbour, $step + 1);
                    $this->matrix[$rowNeighbour][$columnNeighbour] = 0;
                }
            }
        }
    }

    /**
     * Process the reading data and solving the labyrinth.
     *
     * @return void
     */
    public function process(): void
    {
        $this->_read();
        $this->solveLabyrinth($this->initPositionX, $this->initPositionY, 2);
    }
}

$labyrinth = new Labyrinth('labyrinth.in');
$labyrinth->process();
