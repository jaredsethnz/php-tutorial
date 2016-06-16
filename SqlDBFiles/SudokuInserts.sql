
use SudokuCommunityForum;

# SudokuForum :: Creating new users
insert into User values ( null, "CatSurpreme", "Jimmy", "Jones", "jimmyjones@bruh.com", "1", 2, "2015-10-13", "$2y$12$BGEvHX1sHoAOof.v2oaxZ.4Uvd7sn0Fx341SNEkh2ejDuFPqOkLxu", "1", "$2y$12$UmwxrDGvfK0K1WdmM8eoJe.h6oyNP9TehNLCLhn/GWXDKOskPiwja", null);
insert into User values ( null, "EinsteinAtWork", "Jason", "Shiv", "einstein@work.com", "1", 4, "2014-8-20", "$2y$12$BGEvHX1sHoAOof.v2oaxZ.4Uvd7sn0Fx341SNEkh2ejDuFPqOkLxu", "1", "$2y$12$UmwxrDGvfK0K1WdmM8eoJe.h6oyNP9TehNLCLhn/GWXDKOskPiwja", null);
insert into User values ( null, "GamerGod", "Paul", "Jones", "gamergod@work.com", "1", 3, "2011-8-20", "$2y$12$BGEvHX1sHoAOof.v2oaxZ.4Uvd7sn0Fx341SNEkh2ejDuFPqOkLxu", "1", "$2y$12$UmwxrDGvfK0K1WdmM8eoJe.h6oyNP9TehNLCLhn/GWXDKOskPiwja", null);

# SudokuForum :: Creating new forum categories
insert into Category values ('General');
insert into Category values ('Issues');
insert into Category values ('Challenges');
insert into Category values ('FAQ');

insert into Thread values (null, 'General', 'CatSurpreme', 'This is the title1!', curdate());
insert into Thread values (null, 'General', 'CatSurpreme', 'This is the title2!', curdate());
insert into Thread values (null, 'Issues', 'EinsteinAtWork', 'This is the title3!', curdate());
insert into Thread values (null, 'Issues', 'EinsteinAtWork', 'This is the title4!', curdate());
insert into Thread values (null, 'Issues', 'CatSurpreme', 'This is the title5!', curdate());
insert into Thread values (null, 'Challenges', 'EinsteinAtWork', 'This is the title6!', curdate());
insert into Thread values (null, 'Challenges', 'GamerGod', 'This is the title!7', curdate());
insert into Thread values (null, 'Challenges', 'EinsteinAtWork', 'This is the title!8', curdate());
insert into Thread values (null, 'Challenges', 'CatSurpreme', 'This is the title!9', curdate());
insert into Thread values (null, 'FAQ', 'GamerGod', 'This is the title!10', curdate());
insert into Thread values (null, 'FAQ', 'CatSurpreme', 'This is the title!11', curdate());
insert into Thread values (null, 'FAQ', 'GamerGod', 'This is the title!12', curdate());

insert into Post values (null, 6, 'CatSurpreme', 'Hey guys, im new here any tips? Thanks', current_timestamp());
insert into Post values (null, 6, 'EinsteinAtWork', 'Me too, any tips?? Thanks', current_timestamp());

insert into Reply values (null, 2, 'GamerGod', 'Naww no tips here man....', current_timestamp());
insert into Reply values (null, 1, 'GamerGod', 'You too, get ready to get beaten....', current_timestamp());

# SudokuForum :: Creating new Sudoku boards
insert into SudokuBoard values ( null, "9x9", "5", "00000000000000000000" );
insert into SudokuBoard values ( null, "9x9", "5", "11111111111111111111" );
insert into SudokuBoard values ( null, "9x9", "5", "22222222222222222222" );
insert into SudokuBoard values ( null, "9x9", "5", "33333333333333333333" );
insert into SudokuBoard values ( null, "9x9", "5", "44444444444444444444" );

# SudokuForum :: Create new challenges waiting for approved by added players
insert into ChallengeApproval values ( null, "EinsteinAtWork", 2, "9x9", 5, null );
set @challengeApprovalID = ( select last_insert_id() );
insert into UserChallengeApproval values ( @challengeApprovalID, "EinsteinAtWork", 1 );
insert into UserChallengeApproval values ( @challengeApprovalID, "CatSurpreme", null );
insert into UserChallengeApproval values ( @challengeApprovalID, "GamerGod", null );

# SudokuForum :: Update the rows of UserChallengeApproval simulating players acceping the challenges
update UserChallengeApproval set userApproval = true where userNickName = "CatSurpreme";
update UserChallengeApproval set userApproval = true where userNickName = "GamerGod";

# SudokuForum :: Update the rows of UserChallengeApproval simulating players declining the challenges
update UserChallengeApproval set userApproval = false where userNickName = "GamerGod";

# SudokuForum :: call procedure deleteApprovedChallenges to clear challenges which have been approved and turned into an active challenge
call deleteApprovedChallenges();

# SudokuForum :: Update the rows of UserActiveChallenge simulating players completing the game and their times being uploaded
update UserActiveChallenge set completionTime = '1:01:02' where userNickName = "EinsteinAtWork";
update UserActiveChallenge set completionTime = '2:01:02' where userNickName = "CatSurpreme";
update UserActiveChallenge set completionTime = '0:41:02' where userNickName = "GamerGod";

# SudokuForum :: call procedure deleteFinishedChallenges to clear finished active challenges which have been archived to history
call deleteFinishedChallenges();


select * from User;
select * from UserChallengeApproval;
select * from ChallengeApproval;
select * from ActiveChallenge;
select * from UserActiveChallenge;
select * from ChallengeHistory;
select * from UserChallengeHistory;
select * from DeclinedChallenge;
select * from UserDeclinedChallenge;
select * from SudokuBoard;
select * from Post;
select * from Reply;