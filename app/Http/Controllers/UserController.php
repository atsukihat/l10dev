<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\Reviews;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserEditRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    // 新規登録時の処理
    public function create(UserCreateRequest $request)
    {
        $user = User::query()->create([
            'userName' => $request['userName'],
            'userEmail' => $request['userEmail'],
            'password' => Hash::make($request['password']),
            'category' => $request['category'],
            'faculty' => $request['faculty'],
            'department' => $request['department'],
            'admissionYear' => $request['admissionYear'],
            'createdAt' => now(),
            'updatedAt' => now(),
        ]);

        // ユーザーに権限を付与;
        $user->assignRole('user');
        $user->givePermissionTo('user');

        Auth::loginUsingId($user->userId);

        return response()->json(['success' => true, 'id' => $user->userId, 'role' => $user->getRoleNames()]);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $userId = auth()->id();

        $user = User::findOrFail($userId);

        // ユーザー情報を連想配列に格納
        $userData = [
            'userName' => $user->userName,
            'userEmail' => $user->userEmail,
            'universityName' => $user->universityName,
            'category' => $user->category,
            'faculty' => $user->faculty,
            'department' => $user->department,
            'admissionYear' => $user->admissionYear,
            'isActive' => $user->isActive,
            'createdAt' => $user->createdAt,
            'updatedAt' => $user->updatedAt,
            'lastLoginAt' => $user->lastLoginAt,
        ];

        // Reviewsテーブルのlecture_idを基にレビュー一覧の取得
        $reviews = Reviews::where('userId', $userId)
            // ->select('attendance_year', 'attendance_confirm', 'weekly_assignments', 'midterm_assignments', 'final_assignments')
            ->select('reviewId', 'attendanceYear', 'attendanceConfirm', 'weeklyAssignments', 'midtermAssignments', 'finalAssignments', 'pastExamPossession', 'grades', 'creditLevel', 'interestLevel', 'skillLevel', 'comments', 'createdAt')
            ->get();

        $reviewInfo = $reviews->map(function ($review) {

            $userName = Reviews::find($review->reviewId)->user->userName;
            $userId = Reviews::find($review->reviewId)->user->userId;
            $lectureName = Reviews::find($review->reviewId)->lecture->lectureName;

            return [
                'userId' => $userId,
                'userName' => $userName,
                'lectureName' => $lectureName,
                'reviewId' => $review->reviewId,
                'attendanceYear' => $review->attendanceYear,
                'attendanceConfirm' => $review->attendanceConfirm,
                'weeklyAssignments' => $review->weeklyAssignments,
                'midtermAssignments' => $review->midtermAssignments,
                'finalAssignments' => $review->finalAssignments,
                'pastExamPossession' => $review->pastExamPossession,
                'grades' => $review->grades,
                'comments' => $review->comments,
                'createdAt' => $review->createdAt,
                'skillLevel' => $review->skillLevel,
                'interestLevel' => $review->interestLevel,
                'creditLevel' => $review->creditLevel,
                'totalEvaluation' => ($review->skillLevel + $review->interestLevel + $review->creditLevel) / 3,
            ];
        });

        $data = [
            'userData' => $userData,
            'reviewInfo' => $reviewInfo,
        ];

        // JSON形式でデータを返す
        return response()->json($data);
    }

    public function login(Request $request)
    {

        $userEmail = $request->input('userEmail');
        $password = $request->input('password');

        $user = User::where('userEmail', $userEmail)->first();

        if (Auth::attempt(['userEmail' => $userEmail, 'password' => $password])) {

            $user = Auth::user();
            $user->updateLastLogin();

            return response()->json(['success' => true, 'id' => $user->userId, 'role' => $user->getRoleNames()]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return back();

        //    return redirect('/');
    }

    public function update(UserEditRequest $request)
    {
        $userId = auth()->id();
        $user = User::find($userId);
        $user->update($request->all());
    }

    public function initialValues()
    {
        $userId = auth()->id();
        $user = User::findOrFail($userId);

        // ユーザー情報を連想配列に格納
        $userData = [
            'userName' => $user->userName,
            'userEmail' => $user->userEmail,
            'universityName' => $user->universityName,
            'category' => $user->category,
            'faculty' => $user->faculty,
            'department' => $user->department,
            'admissionYear' => $user->admissionYear,
            'isActive' => $user->isActive,
            'createdAt' => $user->createdAt,
            'updatedAt' => $user->updatedAt,
            'lastLoginAt' => $user->lastLoginAt,
        ];

        // JSON形式でデータを返す
        return response()->json($userData);
    }

    public function sendResetLink(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            // userEmail でユーザー取得
            $user = User::where('userEmail', $request->email)->first();
            if (!$user) {
                return response()->json(['message' => 'ユーザーが見つかりません。'], 404);
            }

            // トークンを作成して通知を送る（自動でメール送信）
            $token = Password::createToken($user);
            $user->sendPasswordResetNotification($token);

            return response()->json(['message' => 'パスワード再設定リンクを送信しました。'], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => '入力データが無効です。', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'サーバーエラーが発生しました。', 'error' => $e->getMessage()], 500);
        }
    }


    public function resetPasswordFromLink(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email|exists:users,userEmail',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // トークンの照合（注意：トークンはハッシュ保存されている）
            $record = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->first();

            if (!$record || !Hash::check($request->token, $record->token)) {
                return response()->json(['message' => 'トークンが無効です。'], 400);
            }

            if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
                return response()->json(['message' => 'トークンの有効期限が切れています。'], 400);
            }

            $user = User::where('userEmail', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return response()->json(['message' => 'パスワードが再設定されました。'], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'パスワードが8文字以上であることを確認してください。パスワードと確認用パスワードが一致していることを確認してください。',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'サーバーエラーが発生しました。',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
