-- Single-line version
-- CREATE TABLE `packlist` (`packID` int(11) NOT NULL AUTO_INCREMENT, `packname` text CHARACTER SET utf8 NOT NULL, `packDisplayName` text CHARACTER SET utf8 NOT NULL, `packType` text CHARACTER SET utf8 NOT NULL, `packAuthor` text CHARACTER SET utf8 NOT NULL, `packDescription` text CHARACTER SET utf8 NOT NULL, `packLanguage` text CHARACTER SET utf8 NOT NULL, `packDifficulty` text CHARACTER SET utf8 NOT NULL, `license` text CHARACTER SET utf8 NOT NULL, `attributes` text CHARACTER SET utf8 NOT NULL, `difficulty` text CHARACTER SET utf8 NOT NULL, PRIMARY KEY (`packID`));

CREATE TABLE `packlist` (
  `packID` int(11) NOT NULL AUTO_INCREMENT,
  `packname` text CHARACTER SET utf8 NOT NULL,
  `packDisplayName` text CHARACTER SET utf8 NOT NULL,
  `packType` text CHARACTER SET utf8 NOT NULL,
  `packAuthor` text CHARACTER SET utf8 NOT NULL,
  `packDescription` text CHARACTER SET utf8 NOT NULL,
  `packLanguage` text CHARACTER SET utf8 NOT NULL,
  `license` text CHARACTER SET utf8 NOT NULL,
  `attributes` text CHARACTER SET utf8 NOT NULL,
  `difficulty` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`packID`)
);
