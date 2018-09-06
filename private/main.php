<?php
class databaseConnector
{
	# SERVER CONFIG #
	private $servername = '';
	private $username = '';
	private $password = '';
	private $database = '';
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
	public function getContent($packname, $contentID)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = $dbConnector->conn->prepare("SELECT ID, contentType, multimediaType, multimediaContent, question, answer1, answer2, answer3, answer4, hint, correctAnswer, questionExtra, title, description FROM (SELECT ID, contentType, multimediaType, multimediaContent, question, answer1, answer2, answer3, answer4, hint, correctAnswer, questionExtra, null as title, null as description FROM pack_".$packname."_abcd UNION SELECT ID, contentType, multimediaType, multimediaContent, question, answer1, answer2, answer3, answer4, hint, correctAnswer, questionExtra, null as title, null as description FROM pack_".$packname."_tf UNION SELECT ID, contentType, null as multimediaType, null as multimediaContent, null as question, null as answer1, null as answer2, null as answer3, null as answer4, null as hint, null as correctAnswer, null as questionExtra, title, description FROM pack_".$packname."_info) as t WHERE ID=?");
		$query->bind_param('i', $contentID);
		$query->execute();
		$results = $query->get_result();
		$row = $results->fetch_assoc();
		return $row;
	}
	
	public function getContentCount($packname)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = 'SELECT COUNT(*) as rowscount FROM (SELECT ID FROM pack_'.$packname.'_abcd UNION SELECT ID FROM pack_'.$packname.'_tf UNION SELECT ID FROM pack_'.$packname.'_info) as t;';
		$results = $dbConnector->conn->query($query);
		$questionsCount = 0;
		while ($row = $results->fetch_assoc()) {
			$questionsCount = $row['rowscount'];
		}
		return $questionsCount;
	}
	
	public function getQuestionsCount($packname)
	{
		$dbConnector = new databaseConnector();
		$dbConnector->connectToDatabase();
		$query = 'SELECT COUNT(*) as rowscount FROM (SELECT ID FROM pack_'.$packname.'_abcd UNION SELECT ID FROM pack_'.$packname.'_tf) as t;';
		$results = $dbConnector->conn->query($query);
		$questionsCount = 0;
		while ($row = $results->fetch_assoc()) {
			$questionsCount = $row['rowscount'];
		}
		return $questionsCount;
	}
}

class lifelinesManager
{
	public function getHintText($contentID, $packname)
	{
		$queManager = new questionsManager();
		$row = $queManager->getContent($packname, $contentID);
		return $row['hint'];
	}
	
	public function getFiftyAnswers($contentID, $packname)
	{
		$queManager = new questionsManager();
		$row = $queManager->getContent($packname, $contentID);
		$correctAnswer = $row['correctAnswer'];
		$incorrectAnswers = ['a', 'b', 'c', 'd'];
		unset($incorrectAnswers[$correctAnswer - 1]);
		$randomIncorrectAnswers = array_rand($incorrectAnswers, 2);
		$answersToReturn = $incorrectAnswers[$randomIncorrectAnswers[0]].$incorrectAnswers[$randomIncorrectAnswers[1]];
		return $answersToReturn;
	}
}
?>
