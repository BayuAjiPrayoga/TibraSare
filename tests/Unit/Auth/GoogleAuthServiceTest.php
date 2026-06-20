<?php

namespace Tests\Unit\Auth;

use App\Enums\UserRole;
use App\Models\User;
use App\Services\Auth\GoogleAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Tests\TestCase;

class GoogleAuthServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_user_is_created_as_receptionist_with_verified_email(): void
    {
        $service = app(GoogleAuthService::class);

        $user = $service->findOrCreateUser(new FakeGoogleUser(
            id: 'google-123',
            name: 'Google Receptionist',
            email: 'google@example.com',
        ));

        $this->assertDatabaseHas('users', [
            'email' => 'google@example.com',
            'google_id' => 'google-123',
            'role' => UserRole::Receptionist->value,
        ]);

        $this->assertTrue($user->email_verified_at->isPast() || $user->email_verified_at->isCurrentSecond());
        $this->assertSame(UserRole::Receptionist, $user->role);
    }

    public function test_existing_user_is_linked_to_google_account_by_email(): void
    {
        $existingUser = User::factory()->create([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'google_id' => null,
            'email_verified_at' => null,
            'role' => UserRole::Admin,
        ]);

        $service = app(GoogleAuthService::class);

        $user = $service->findOrCreateUser(new FakeGoogleUser(
            id: 'google-456',
            name: 'Existing Google User',
            email: 'existing@example.com',
        ));

        $this->assertTrue($existingUser->is($user));
        $this->assertSame('google-456', $user->fresh()->google_id);
        $this->assertSame(UserRole::Admin, $user->fresh()->role);
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}

class FakeGoogleUser implements SocialiteUserContract
{
    public function __construct(
        private readonly string $id,
        private readonly ?string $name,
        private readonly ?string $email,
        private readonly ?string $nickname = null,
        private readonly ?string $avatar = null,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }
}
