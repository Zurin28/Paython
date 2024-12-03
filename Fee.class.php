<?php 
require_once "database.class.php";
class Fee{

public $FeeID;
public $FeeName;
public $Amount;
public $Duedate;
public $Description;
protected $db;

function __construct() {
    $this->db = new Database();
}

function viewFees() {
    $sql = "SELECT * from Fees";
    $qry = $this->db->connect()->prepare($sql);
    if ($qry -> execute()){
        $data = $qry->fetchAll();
    }
    return $data;
  }



}

$obj = new Fee;
$objInfo = $obj->viewFees();
?><pre>
<?php var_dump($objInfo);?>
</pre>
