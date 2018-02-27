-- Single-line version
-- CREATE TABLE `answers_examplepack` (`answerID` int(11) NOT NULL AUTO_INCREMENT, `username` text CHARACTER SET utf8 NOT NULL, `userAnswers` text CHARACTER SET utf8 NOT NULL, PRIMARY KEY (`answerID`));

CREATE TABLE `answers_examplepack` (
  `answerID` int(11) NOT NULL AUTO_INCREMENT,
  `username` text CHARACTER SET utf8 NOT NULL,
  `userAnswers` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`answerID`)
);
