drop database if exists SudokuCommunityForum;

create database SudokuCommunityForum;

use SudokuCommunityForum;

create table User(
	userID int auto_increment not null primary key,
	nickName varchar(25) not null unique key,
    firstName varchar(25) not null,
    lastName varchar(25) not null,
	email varchar(45) not null unique key,
    challengeable boolean default false,
    rank tinyint default 1,
    joinDate date not null,
    password char(60) not null,
    activated boolean default false,
    activationHash char(60) not null,
    profilePic varchar(50)
);

create table SudokuBoard(
	boardID int primary key auto_increment,
    boardSize char(3) not null,
    difficulty tinyint not null,
    boardValues varchar(81) not null unique key
);

create table ChallengeApproval(
	challengeApprovalID int primary key auto_increment,
    userID int not null,
    duration int not null,
    boardSize char(3) not null,
    difficulty tinyint not null,
    challengeApproved boolean default null,
    foreign key (userID) references User (userID)
);

create table UserChallengeApproval(
	challengeApprovalID int auto_increment,
    userID int not null,
    userApproval boolean default null,
    primary key (challengeApprovalID, userID),
    foreign key (challengeApprovalID) references ChallengeApproval (challengeApprovalID),
    foreign key (userID) references User (userID)
);

create table ActiveChallenge(
	activeChallengeID int primary key auto_increment,
	boardID int not null,
    dateStart date not null,
    dateEnd date not null,
    challengeFinished boolean default false
);

create table UserActiveChallenge(
	activeChallengeID int auto_increment,
    userID int not null,
    completionTime time,
    primary key(activeChallengeID, userID),
    foreign key (activeChallengeID) references ActiveChallenge (activeChallengeID),
    foreign key (userID) references User (userID)
);

create table ChallengeHistory(
	challengeHistoryID int primary key auto_increment,
    boardID int not null,
    dateArchived date not null,
    userID int not null,
    foreign key (boardID) references SudokuBoard (boardID),
    foreign key (userID) references User (userID)
);

create table UserChallengeHistory(
	challengeHistoryID int auto_increment,
    userID int not null,
    finishingNumber smallInt default 0,
    primary key (challengeHistoryID, userID),
    foreign key (challengeHistoryID) references ChallengeHistory (challengeHistoryID),
    foreign key (userID) references User (userID)
);

create table DeclinedChallenge(
	declinedChallengeID int primary key auto_increment,
    dateDeclined date not null,
    challengerUserID int not null
);

create table UserDeclinedChallenge(
	declinedChallengeID int auto_increment,
    userID int not null,
    declined boolean default false,
    primary key (declinedChallengeID, userID),
    foreign key (declinedChallengeID) references DeclinedChallenge (declinedChallengeID),
    foreign key (userID) references User (userID)
);

create table Category(
	categoryName varchar(50) not null primary key
);

create table Thread(
	threadID int auto_increment not null primary key,
    categoryName varchar(50) not null,
    userID int not null,
    title varchar(50) not null,
    threadDate date not null,
    foreign key (categoryName) references Category (categoryName),
    foreign key (userID) references User (userID)
);

create table Post(
	postID int auto_increment not null primary key,
    threadID int not null,
    userID int not null,
    postContent varchar(300) not null,
    postDate date not null,
    foreign key (threadID) references Thread (threadID),
    foreign key (userID) references User (userID)
);

create table Reply(
	replyID int auto_increment not null primary key,
    postID int not null,
    userID int not null,
    replyContent varchar(300) not null,
    replyDate date not null,
    foreign key (postID) references Post (postID),
    foreign key (userID) references User (userID)
);