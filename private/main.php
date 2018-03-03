<?php
class databaseConnector
{
	# SERVER CONFIG #
	private $servername = 'servername';
	private $username = 'username';
	private $password = 'password';
	private $database = 'database';
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
}

class questionsManager
{
	public function getQuestion($packname, $questionID)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = $dbConnector->conn->prepare("SELECT * FROM pack_".$packname." WHERE questionID=?");
		$query->bind_param('s', $questionID);
		$query->execute();
		$results = $query->get_result();
		$row = $results->fetch_assoc();
		return $row;
	}
	
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
}

class lifelinesManager
{
	public function getHintText($questionID, $packname)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = $dbConnector->conn->prepare("SELECT * FROM pack_".$packname." WHERE questionID=?");
		$query->bind_param('s', $questionID);
		$query->execute();
		$results = $query->get_result();
		$row = $results->fetch_assoc();
		return $row['hint'];
	}
	
	public function getFiftyAnswers($questionID, $packname)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = $dbConnector->conn->prepare("SELECT * FROM pack_".$packname." WHERE questionID=?");
		$query->bind_param('s', $questionID);
		$query->execute();
		$results = $query->get_result();
		$row = $results->fetch_assoc();
		$correctAnswer = $row['correctAnswer'];
		$incorrectAnswers = ['a', 'b', 'c', 'd'];
		unset($incorrectAnswers[$correctAnswer - 1]);
		$randomIncorrectAnswers = array_rand($incorrectAnswers, 2);
		$answersToReturn = $incorrectAnswers[$randomIncorrectAnswers[0]].$incorrectAnswers[$randomIncorrectAnswers[1]];
		return $answersToReturn;
	}
}
?>
