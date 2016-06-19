
use SudokuCommunityForum;

# SudokuForum ::
# SudokuForum :: Get user nicknames, ranks and whether or not they are challengable
# Note I used the like and wilcard to bring up all users as im not specifically searching for one
select nickName, rank, challengeable from User where nickName like "%"; 

# SudokuForum ::
# SudokuForum :: Get user details for profile editing
select firstName, lastName, nickName, email, challengeable from User where nickName = "CatSurpreme";

# SudokuForum ::
# SudokuForum :: Update user details to deny challenges
update User set challengeable = false where nickName = "CatSurpreme";

# SudokuForum ::
# SudokuForum :: Get user's active challenges with the opponents names and time left
select group_concat( ( case when userNickName = 'GamerGod' then 'You' else userNickName end ) separator ', ' ) as 'Opponent(s)', concat_ws( ' ', dateEnd - dateStart, 'Day(s)' ) as 'TimeLeft' from UserActiveChallenge
inner join ActiveChallenge on
UserActiveChallenge.activeChallengeID = ActiveChallenge.activeChallengeID
where UserActiveChallenge.activeChallengeID in ( select UserActiveChallenge.activeChallengeID from UserActiveChallenge where userNickName = 'EinsteinAtWork' ) group by UserActiveChallenge.activeChallengeID;

# SudokuForum ::
# SudokuForum :: Get user's challenge history with the opponents the date which the challenge ended and whether or not it was a win or a loss
select group_concat( ( case when userNickName = 'GamerGod' then 'You' else userNickName end ) separator ', ' ) as 'Opponent(s)', dateArchived, boardSize, difficulty, ( case when winnerNickName = 'GamerGod' then 'Win' else 'Loss' end ) as Outcome
from UserChallengeHistory 
inner join ChallengeHistory on
UserChallengeHistory.challengeHistoryID = ChallengeHistory.challengeHistoryID
inner join SudokuBoard on
ChallengeHistory.boardID = SudokuBoard.boardID
where UserChallengeHistory.challengeHistoryID in ( select challengeHistoryID from UserChallengeHistory where userNickName = 'GamerGod' )
group by UserChallengeHistory.challengeHistoryID;


# SudokuForum ::
# SudokuForum :: Get user's declined challenge history
select group_concat( ( case when UserDeclinedChallenge.challengerNickName = 'GamerGod' then 'You' else UserDeclinedChallenge.challengerNickName end ) separator ', ' ) as 'Opponents', dateDeclined, group_concat( (case when UserDeclinedChallenge.declined = true then UserDeclinedChallenge.challengerNickName else '' end ) separator '' ) as declinedBy, ( case when DeclinedChallenge.challengerNickName = 'GamerGod' then 'You' else DeclinedChallenge.challengerNickName end ) as challengerNickName
from UserDeclinedChallenge 
inner join DeclinedChallenge on
UserDeclinedChallenge.declinedChallengeID = DeclinedChallenge.declinedChallengeID
where UserDeclinedChallenge.declinedChallengeID in ( select declinedChallengeID from UserDeclinedChallenge where challengerNickName = 'GamerGod' )
group by UserDeclinedChallenge.declinedChallengeID;


# SudokuForum ::
# SudokuForum :: Get user's challenges pending approval
select challengerNickName, rank, group_concat( ( case when userNickName = 'GamerGod' then 'You' else userNickName end ) separator ', ' ) as 'Opponent(s)', concat_ws(' ', duration, 'Day(s)') as 'Duration'
from UserChallengeApproval
inner join ChallengeApproval on
UserChallengeApproval.challengeApprovalID = ChallengeApproval.challengeApprovalID
inner join User on
ChallengeApproval.challengerNickName = User.nickName
where UserChallengeApproval.challengeApprovalID in ( select challengeApprovalID from UserChallengeApproval where userNickName = 'GamerGod' )
group by UserChallengeApproval.challengeApprovalID;


# SudokuForum ::
# SudokuForum :: Get Declined challenges, opponents, who the challenger was and the opponent who declined
select group_concat( userNickName separator ', ' ) as 'Opponent(s)', dateDeclined as 'Date', challengerNickName as 'Challenger', group_concat( ( case when declined = true then userNickName else null end ) separator ',') as 'Declined by'
from UserDeclinedChallenge
inner join DeclinedChallenge on
UserDeclinedChallenge.declinedChallengeID = DeclinedChallenge.declinedChallengeID
group by UserDeclinedChallenge.declinedChallengeID;



# SudokuForum ::
# SudokuForum :: Get user's nicknames which still need to approve a challenge
select concat_ws( ' ', 'Awaiting Approval of', group_concat( ( case when userNickName = 'GamerGod' then 'You' else userNickName end ) separator ', ' ) ) as 'Awaiting Approval'
from UserChallengeApproval
where challengeApprovalID = 2
and userApproval is null
group by challengeApprovalID;

# SudokuForum ::
# SudokuForum :: Get Thread last post information
SELECT IFNULL((SELECT concat('Last post on ', postDate, ', by ', nickName) as 'lastPost' FROM Post where threadID = '6' ORDER BY postDate DESC LIMIT 1), 'No posts...');


