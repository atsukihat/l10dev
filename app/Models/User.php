<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; // ← ログを追加

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    protected $primaryKey = 'userId';
    public $timestamps = false;

    protected $fillable = [
        'userName',
        'userEmail',
        'password',
        'universityName',
        'category',
        'faculty',
        'department',
        'admissionYear',
        'isActive',
        'createdAt',
        'updatedAt',
        'lastLoginAt',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class, 'userId', 'uuid');
    }

    /**
     * パスワードリセット用に userEmail を返す
     */
    public function getEmailForPasswordReset()
    {
        return $this->userEmail;
    }

    /**
     * パスワードリセット通知を送信する
     */
    public function sendPasswordResetNotification($token)
    {
        $resetUrl = url('/password/reset/' . $token . '?email=' . urlencode($this->userEmail));

        Mail::raw("以下のリンクからパスワードをリセットできます：\n\n" . $resetUrl, function ($message) {
            $message->to($this->userEmail)
                ->subject('パスワードリセットリンク');
        });

        Log::debug('Mail::raw によりメール送信を試みました: ' . $resetUrl);
    }

    public function updateLastLogin()
    {
        $this->update(['lastLoginAt' => now()]);
    }

    public function updatecreatedAt()
    {
        $this->update(['createdAt' => now()]);
    }

    public function updateUpdatedAt()
    {
        $this->update(['updatedAt' => now()]);
    }
}
