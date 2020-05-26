<?php
    class Book{
        public $bookId;
        public $title;
        public $isbn;
        public $description;
        public $authorId;
        public $publisherId;
        public $year;
        public $url;
        public $categoryId;
        public $price;
        public $lang;
        public $page;
        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function create(){
            $sql = 'INSERT INTO book 
                        SET
                            title = :title,
                            isbn = :ISBN,
                            description = :description,
                            author_id = :authorId,
                            publisher_id = :publisherId,
                            category_id = :categoryId,
                            url = :url,
                            year = :year,
                            price = :price,
                            lang = :lang,
                            page = :page';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':title',$this->title);
            $stat->bindParam(':ISBN',$this->isbn);
            $stat->bindParam(':description',$this->description);
            $stat->bindParam(':authorId',$this->authorId);
            $stat->bindParam(':publisherId',$this->publisherId);
            $stat->bindParam(':categoryId',$this->categoryId);
            $stat->bindParam(':url',$this->url);
            $stat->bindParam(':year',$this->year);
            $stat->bindParam(':price',$this->price);
            $stat->bindParam(':lang',$this->lang);
            $stat->bindParam(':page',$this->page);

            try{
                if($stat->execute()){
                    return true;
                }
                return false;
            } catch(PDOException $e) {
                echo json_encode(array(
                    'error'=>$e->getMessage()
                ));
                return false;
            }
        }

        public function read(){
            $sql = 'SELECT b.id as id,
                        title,
                        b.description as description,
                        a.authorName as authorName,
                        p.publisher as publisherName,
                        c.category as categoryName,
                        isbn,
                        url,
                        year,
                        price,
                        page,
                        language
                    FROM book b 
                        LEFT JOIN author a 
                        ON a.id = b.author_id
                        LEFT JOIN publisher p
                        ON p.id = b.publisher_id
                        LEFT JOIN category c
                        ON c.id = b.category_id
                        LEFT JOIN lang l
                        ON l.id = b.lang';
            $stat = $this->conn->prepare($sql);
            $stat->execute();
            return $stat;
        }

        public function read_book(){
            $sql = 'SELECT b.id as id,
                        title,
                        a.id as authorId,
                        p.id as publisherId,
                        c.id as categoryId,
                        b.description as description,
                        a.authorName as authorName,
                        p.publisher as publisherName,
                        c.category as categoryName,
                        isbn,
                        url,
                        year,
                        page,
                        language,
                        lang,
                        price,
                        IF(s.value > 0, "in" ,"out") as stock
                    FROM book b 
                        LEFT JOIN author a 
                        ON a.id = b.author_id
                        LEFT JOIN publisher p
                        ON p.id = b.publisher_id
                        LEFT JOIN category c
                        ON c.id = b.category_id
                        LEFT JOIN lang l
                        ON l.id = b.lang
                        LEFT JOIN stock s
                        ON s.book_id = b.id
                    WHERE 
                        b.title = :title';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':title',$this->title);
            $stat->execute();
            return $stat;
        }

        public function new_arrivals(){
            $sql = 'SELECT b.id as id,
                        title,
                        b.description as description,
                        a.authorName as authorName,
                        p.publisher as publisherName,
                        c.category as categoryName,
                        isbn,
                        url,
                        year,
                        price,
                        page,
                        lang,
                        IF(s.value > 0, "in" ,"out") as stock
                    FROM book b 
                        LEFT JOIN author a 
                        ON a.id = b.author_id
                        LEFT JOIN publisher p
                        ON p.id = b.publisher_id
                        LEFT JOIN category c
                        ON c.id = b.category_id
                        LEFT JOIN lang l
                        ON l.id = b.lang
                        LEFT JOIN stock s
                        ON s.book_id = b.id
                    WHERE 
                        year > year(curdate())-5
                         ';
            $stat = $this->conn->prepare($sql);
            $stat->execute();
            return $stat;
        }

        public function update(){
            $sql = 'UPDATE book
                        SET 
                            title = :title,
                            isbn = :ISBN,
                            description = :description,
                            author_id = :authorId,
                            publisher_id = :publisherId,
                            category_id = :categoryId,
                            url = :url,
                            year = :year,
                            price = :price,
                            page = :page,
                            lang = :lang
                        WHERE
                            id=:bookId';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':bookId',$this->bookId);
            $stat->bindParam(':title',$this->title);
            $stat->bindParam(':ISBN',$this->isbn);
            $stat->bindParam(':description',$this->description);
            $stat->bindParam(':authorId',$this->authorId);
            $stat->bindParam(':publisherId',$this->publisherId);
            $stat->bindParam(':categoryId',$this->categoryId);
            $stat->bindParam(':url',$this->url);
            $stat->bindParam(':year',$this->year);
            $stat->bindParam(':price',$this->price);
            $stat->bindParam(':page',$this->page);
            $stat->bindParam(':lang',$this->lang);

            try{
                if($stat->execute()){
                    return true;
                }
                return false;
            } catch(PDOException $e) {
                echo json_encode(array(
                    'error'=>$e->getMessage()
                ));
                return false;
            }
        }

        public function create_stock(){
            $result = $this->read_book();
            $row = $result->fetch();
            $this->bookId = $row->id;
            $sql = 'INSERT INTO stock
                        SET 
                            book_id=:bookId';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':bookId',$this->bookId);

            try{
                if($stat->execute()){
                    return true;
                }
                return false;
            } catch(PDOException $e) {
                echo json_encode(array(
                    'error'=>$e->getMessage()
                ));
                return false;
            }
        }

    }