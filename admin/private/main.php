<?php
class databaseConnector
{
	# SERVER CONFIG #
	private $servername = 'servername';
	private $username = 'username';
	private $password = 'password';
	private $database = 'database';
	private $adminPassword = 'adminpassword';
	# END OF CONFIG #
	
	# Connection variable
	public $conn;
	function connectToDatabase()
	{
		# Database connection
		$this->conn = new mysqli($this->servername, $this->username, $this->password);
		$this->conn->select_db($this->database);
		$utf8Query = 'SET NAMES utf8;';
		$this->conn->query($utf8Query);
	}
	
	function isValidAdminPassword($passwordToCheck)
	{
		if ($passwordToCheck == $this->adminPassword)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
class questionsManager
{
	public function getQuestionsCount($packname)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = 'SELECT COUNT(*) as rowscount FROM pack_'.$packname;
		$results = $dbConnector->conn->query($query);
		$questionsCount = 0;
		while ($row = $results->fetch_assoc()) {
			$questionsCount = $row['rowscount'];
		}
		return $questionsCount;
	}
	
	public function getValidAnswer($questionID, $packname)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = $dbConnector->conn->prepare("SELECT * FROM pack_".$packname." WHERE questionID=?");
		$query->bind_param('s', $questionID);
		$query->execute();
		$results = $query->get_result();
		$row = $results->fetch_assoc();
		return $row['correctAnswer'];
	}
}
class userAnswersManager
{
	
	public function insertUserAnswer($userUsername, $userAnswers, $packname)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = $dbConnector->conn->prepare("INSERT INTO answers_".$packname." (username, userAnswers) VALUES (?,?)");
		$query->bind_param('ss', $userUsername, $userAnswers);
		$results = $query->execute();
	}
	
	public function getUsersAnswers($packname)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = "SELECT * FROM answers_".$packname;
		$results = $dbConnector->conn->query($query);
		return $results;
	}
	
	public function getQuestionPackAnswers($packname)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = "SELECT correctAnswer FROM pack_".$packname;
		$results = $dbConnector->conn->query($query);
		return $results;
	}
}

class questionPacksManager
{
	public function getQuestionPacks()
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = "SELECT * FROM packlist";
		$results = $dbConnector->conn->query($query);
		return $results;
	}
	
	public function deleteQuestionPack($packname, $deleteUserAnswers)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = $dbConnector->conn->prepare("DELETE FROM packlist WHERE packname=?;");
		$query->bind_param('s', $packname);
		$results = $query->execute();
		$query = "DROP TABLE IF EXISTS pack_".$packname.";";
		$results = $dbConnector->conn->query($query);
		if ($deleteUserAnswers)
		{
			$query = "DROP TABLE IF EXISTS answers_".$packname.";";
			$results = $dbConnector->conn->query($query);
		}
	}
	
	public function importQuestionPack($packFilename)
	{
		$csvFile = file('import/'.$packFilename);
		$data = str_getcsv($csvFile[0]);
		$packV_packname = $data[0];
		$packV_packDisplayName = $data[1];
		$packV_packType = $data[2];
		$packV_packAuthor = $data[3];
		$packV_packDescription = $data[4];
		$packV_packLanguage = $data[5];
		$packV_packLicense = $data[6];
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = $dbConnector->conn->prepare("INSERT INTO packlist (packname, packDisplayName, packType, packAuthor, packDescription, packLanguage, license) VALUES (?, ?, ?, ?, ?, ?, ?);");
		$query->bind_param('sssssss', $packV_packname, $packV_packDisplayName, $packV_packType, $packV_packAuthor, $packV_packDescription, $packV_packLanguage, $packV_packLicense);
		$results = $query->execute();
		$query = "CREATE TABLE pack_".$packV_packname." (`questionID` int(11) NOT NULL AUTO_INCREMENT, `question` text CHARACTER SET utf8 NOT NULL, `hint` text CHARACTER SET utf8 NOT NULL, `answer1` text CHARACTER SET utf8 NOT NULL, `answer2` text CHARACTER SET utf8 NOT NULL, `answer3` text CHARACTER SET utf8 NOT NULL, `answer4` text CHARACTER SET utf8 NOT NULL, `correctAnswer` text CHARACTER SET utf8 NOT NULL, PRIMARY KEY (`questionID`));";
		$results = $dbConnector->conn->query($query);
		if ($packV_packType == 'test')
		{
			$query = "CREATE TABLE IF NOT EXISTS answers_".$packV_packname." (`answerID` int(11) NOT NULL AUTO_INCREMENT, `username` text CHARACTER SET utf8 NOT NULL, `userAnswers` text CHARACTER SET utf8 NOT NULL, PRIMARY KEY (`answerID`));";
			$results = $dbConnector->conn->query($query);
		}
		$csvImportFile = file('import/'.$packFilename);
		$i = 0;
		foreach($csvImportFile as $line)
		{
			if ($i!=0)
			{
				$query1 = $dbConnector->conn->prepare("INSERT INTO pack_".$packV_packname." (question, hint, answer1, answer2, answer3, answer4, correctAnswer) VALUES (?, ?, ?, ?, ?, ?, ?);");
				$query1->bind_param('sssssss', str_getcsv($line)[0], str_getcsv($line)[1], str_getcsv($line)[2], str_getcsv($line)[3], str_getcsv($line)[4], str_getcsv($line)[5], str_getcsv($line)[6]);
				$results = $query1->execute();
			}
			$i++;
		}
	}
	
	public function clearUserAnswers($packname)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = "TRUNCATE TABLE answers_".$packname;
		$results = $dbConnector->conn->query($query);
	}
	
	public function clearSingleAnswer($packname, $answerID)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = $dbConnector->conn->prepare("DELETE FROM answers_".$packname." WHERE answerID=?;");
		$query->bind_param('s', $answerID);
		$results = $query->execute();
	}
}
?>
