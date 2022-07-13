<?php

include_once "Reposotory/PeopleRepository.php";

class People implements PeopleRepository {

    // подключение к базе данных и имя таблицы
    private $conn;
    private $table_name = "people";

    private $id;
    private $name;
    private $surname;
    private $date_of_birth;
    private $gender;
    private $city_of_birth;

    public function __construct($db, $id = null, $name = null, $surname = null,
                                $date_of_birth = null, $gender = null, $city_of_birth = null)
    {
        $this->conn = $db;

        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->date_of_birth = $date_of_birth;
        $this->gender = $gender;
        $this->city_of_birth = $city_of_birth;

        if ($id == null){
            $this->validate();
            $this->save();
        } else {
            $this->findById();
        }

    }

    public function save()
    {
        $sql = "INSERT INTO $this->table_name (id, name, surname, date_of_birth, gender, city_of_birth) 
                VALUES (:id, :name, :surname, :date_of_birth, :gender, :city_of_birth)";

        $stmt= $this->conn->prepare($sql);
        $stmt->execute($this->getData());
        $this->setId($this->conn->lastInsertId());

        return $stmt;
    }

    public function update()
    {
        $sql = "UPDATE $this->table_name SET name = :name, surname = :surname, date_of_birth = :date_of_birth, 
                 gender = :gender, city_of_birth = :city_of_birth WHERE id = :id";

        $stmt= $this->conn->prepare($sql);
        $stmt->execute($this->getData());

        return $stmt;
    }

    public function delete()
    {
        $query = "DELETE FROM $this->table_name WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([":id" => $this->getId()]);

        return $stmt;
    }

    public function findById()
    {
        $query = "SELECT * FROM $this->table_name WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([":id" => $this->getId()]);

        $this->setData($stmt->fetch(PDO::FETCH_OBJ));
    }

    public static function convertToAge($people)
    {
        return DateTime::createFromFormat('Y-m-d', $people->getDateOfBirth())
            ->diff(new DateTime('now'))
            ->y;
    }

    public function getStdClass()
    {
        $people = new stdClass();

        $people->id            = $this->getId();
        $people->name          = $this->getName();
        $people->surname       = $this->getSurname();
        $people->date_of_birth = $this->getDateOfBirth();
        $people->gender        = $this->getGender();
        $people->city_of_birth = $this->getCityOfBirth();

        $people->age           = People::convertToAge($this);
        $people->text_gender   = People::convertToGender($this);

        return $people;
    }

    public static function convertToGender($people)
    {
        return $people->getGender() ? 'female' : 'male';
    }

    public function getData()
    {
        return [
            'id'            => $this->getId(),
            'name'          => $this->getName(),
            'surname'       => $this->getSurname(),
            'date_of_birth' => $this->getDateOfBirth(),
            'gender'        => $this->getGender(),
            'city_of_birth' => $this->getCityOfBirth(),
        ];
    }

    public function setData($people)
    {
        $this->setId($people->id);
        $this->setName($people->name);
        $this->setSurname($people->surname);
        $this->setDateOfBirth($people->date_of_birth);
        $this->setGender($people->gender);
        $this->setCityOfBirth($people->city_of_birth);
    }

    function findAll()
    {
        $query = "SELECT * FROM $this->table_name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    protected function validate(){
        if (!ctype_alpha($this->getName())){
            throw new Exception('Field name is not correct');
        }
        else if (!ctype_alpha($this->getSurname())){
            throw new Exception('Field surname is not correct');
        }
        else if (is_bool($this->getGender())){
            throw new Exception('Field gender is not correct');
        }
    }

    /**
     * @return mixed|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed|null $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getDateOfBirth()
    {
        return $this->date_of_birth;
    }

    /**
     * @param mixed $date_of_birth
     */
    public function setDateOfBirth($date_of_birth)
    {
        $this->date_of_birth = $date_of_birth;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getCityOfBirth()
    {
        return $this->city_of_birth;
    }

    /**
     * @param mixed $city_of_birth
     */
    public function setCityOfBirth($city_of_birth)
    {
        $this->city_of_birth = $city_of_birth;
    }

}