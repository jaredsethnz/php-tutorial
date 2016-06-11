drop database if exists SudokuCommunityForum;

create database SudokuCommunityForum;

use SudokuCommunityForum;

create table User(
	nickName varchar(25) not null unique key,
    firstName varchar(25) not null,
    lastName varchar(25) not null,
	email varchar(45) not null unique key,
    challengeable boolean default false,
    rank tinyint default 1,
    joinDate date not null,
    dob date not null,
    password char(60) not null,
    activated boolean default false,
    activationHash char(60) not null,
    profilePic longblob, 
    primary key (nickName, firstName, lastName, email)
);

create table SudokuBoard(
	boardID int primary key auto_increment,
    boardSize char(3) not null,
    difficulty tinyint not null,
    boardValues varchar(81) not null unique key
);

create table ChallengeApproval(
	challengeApprovalID int primary key auto_increment,
    challengerNickName varchar(25) not null,
    duration int not null,
    boardSize char(3) not null,
    difficulty tinyint not null,
    challengeApproved boolean default null,
    foreign key (challengerNickName) references User (nickName)
);

create table UserChallengeApproval(
	challengeApprovalID int auto_increment,
    userNickName varchar(25) not null,
    userApproval boolean default null,
    primary key (challengeApprovalID, userNickName),
    foreign key (challengeApprovalID) references ChallengeApproval (challengeApprovalID),
    foreign key (userNickName) references User (nickName)
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
    userNickName varchar(25) not null,
    completionTime time,
    primary key(activeChallengeID, userNickName),
    foreign key (activeChallengeID) references ActiveChallenge (activeChallengeID),
    foreign key (userNickName) references User (nickName)
);

create table ChallengeHistory(
	challengeHistoryID int primary key auto_increment,
    boardID int not null,
    dateArchived date not null,
    winnerNickName varchar(25) not null,
    foreign key (boardID) references SudokuBoard (boardID),
    foreign key (winnerNickName) references User (nickName)
);

create table UserChallengeHistory(
	challengeHistoryID int auto_increment,
    userNickName varchar(25) not null,
    finishingNumber smallInt default 0,
    primary key (challengeHistoryID, userNickName),
    foreign key (challengeHistoryID) references ChallengeHistory (challengeHistoryID),
    foreign key (userNickName) references User (nickName)
);

create table DeclinedChallenge(
	declinedChallengeID int primary key auto_increment,
    dateDeclined date not null,
    challengerNickName varchar(25) not null
);

create table UserDeclinedChallenge(
	declinedChallengeID int auto_increment,
    userNickName varchar(25) not null,
    declined boolean default false,
    primary key (declinedChallengeID, userNickName),
    foreign key (declinedChallengeID) references DeclinedChallenge (declinedChallengeID),
    foreign key (userNickName) references User (nickName)
);

create table Category(
	categoryName varchar(50) not null primary key
);

create table Thread(
	threadID int auto_increment not null primary key,
    categoryName varchar(50) not null,
    userNickName varchar(25) not null,
    title varchar(50) not null,
    threadDate date not null,
    foreign key (categoryName) references Category (categoryName),
    foreign key (userNickName) references User (nickName)
);

create table Post(
	postID int auto_increment not null primary key,
    threadID int not null,
    userNickName varchar(25) not null,
    postContent varchar(300) not null,
    postDate date not null,
    foreign key (threadID) references Thread (threadID),
    foreign key (userNickName) references User (nickName)
);

create table Reply(
	replyID int auto_increment not null primary key,
    postID int not null,
    userNickName varchar(25) not null,
    replyContent varchar(300) not null,
    replyDate date not null,
    foreign key (postID) references Post (postID),
    foreign key (userNickName) references User (nickName)
);