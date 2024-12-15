<?php
class OutOfSpaceException extends Exception {}
class OutOfInkException extends Exception {}



class Paper {
    private int $maxSymbols;
    private string $content;

    public function __construct(int $maxSymbols = 1024) {
        $this->maxSymbols = $maxSymbols;
        $this->content = '';
    }
    public function __toString(): string {
        return "Бумага: (" . mb_strlen($this->content) . "/" . $this->maxSymbols . ")";
    }
    public function addContent(string $message): void {
        $available = $this->maxSymbols - mb_strlen($this->content);

        if ($available <= 0) {
            throw new OutOfSpaceException("Нема місця");
        }
        if (mb_strlen($message) > $available) {
            $this->content .= substr($message, 0, $available);
            throw new OutOfSpaceException("Не хвате місця");
        }
        $this->content .= $message;
    }
    public function show(): void {
        echo $this->content . PHP_EOL;
    }
}



class Pen {
    private int $symbols;
    private int $maxSymbols;

    public function __construct(int $maxSymbols = 4096) {
        $this->symbols = $maxSymbols;
        $this->maxSymbols = $maxSymbols;
    }

    public function __toString(): string {
        return "Ручка: ({$this->symbols}/{$this->maxSymbols})";
    }
    public function write(Paper $paper, string $message): void {
        if ($this->symbols === 0) {
            throw new OutOfInkException("Нема чорнил");
        }
        $writeLength = min(mb_strlen($message), $this->symbols); #пишемо - до кінця!)
        $paper->addContent(substr($message, 0, $writeLength));
        $this->symbols -= $writeLength;

        if ($this->symbols === 0) {
            throw new OutOfInkException("Чорнила закінчились");
        }
    }
}



try {
    $pen = new Pen();
    $paper = new Paper();
    echo $pen . PHP_EOL;
    echo $paper . PHP_EOL;
    $pen->write($paper, "Лол кек, ЧЕБУРЄК!!!!!");
    $paper->show();
    echo $pen . PHP_EOL;
    echo $paper . PHP_EOL;
    $pen->write($paper, str_repeat("Ойойой, що це таке? ! ", 20));
    $pen->write($paper, str_repeat("фівл  дтфідлворфідл вофідлводлфіов", 60));
    $pen->write($paper, str_repeat("фі влдтфідлворфідлвофі длводлфіов", 10));
} catch (OutOfSpaceException | OutOfInkException $e) {
    echo "Exception: " . $e->getMessage() . PHP_EOL;
}
?>
