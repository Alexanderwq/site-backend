<?php

namespace App\Model\User\Entity\User;

use DateTimeImmutable;
use DomainException;
use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity,
    ORM\Table(name: 'user_users'),
    ORM\HasLifecycleCallbacks,
    ORM\UniqueConstraint(columns: ['email']),
    ORM\UniqueConstraint(columns: ['reset_token_token']),
]
class User
{
    public const string STATUS_BLOCK = 'blocked';

    public const string STATUS_WAIT = 'wait';

    public const string STATUS_ACTIVE = 'active';

    public const string STATUS_NEW = 'new';

    #[ORM\Column(type: 'string', length: 16)]
    private string $status;

    #[ORM\Column(name: 'email', type: EmailType::NAME, nullable: true)]
    private ?Email $email = null;

    #[ORM\Column(name: 'password_hash', type: 'string', nullable: true)]
    private ?string $passwordHash = null;

    #[ORM\Column(name: 'confirm_token', type: 'string', nullable: true)]
    private ?string $confirmToken;

    #[ORM\Column(name: 'new_email_token', type: 'string', nullable: true)]
    private ?string $newEmailToken = null;

    #[ORM\Embedded(class: ResetToken::class, columnPrefix: 'reset_token_')]
    private ?ResetToken $resetToken = null;

    #[ORM\Column(type: RoleType::NAME)]
    private Role $role;

    private function __construct(
        #[ORM\Column(type: IdType::NAME), ORM\Id]
        private readonly Id $id,
        #[ORM\Column(type: 'datetime_immutable')]
        private readonly DateTimeImmutable $date,
        #[ORM\Embedded(class: Name::class)]
        private Name $name,
    ) {
        $this->status = self::STATUS_NEW;
        $this->role = Role::user();
    }

    public static function create(
        Id $id,
        DateTimeImmutable $date,
        Name $name,
        Email $email,
        string $hash,
    ): self {
        $user = new self($id, $date, $name);
        $user->email = $email;
        $user->passwordHash = $hash;
        $user->status = self::STATUS_ACTIVE;

        return $user;
    }

    public static function signUpByEmail(
        Id $id,
        DateTimeImmutable $date,
        Name $name,
        Email $email,
        string $passwordHash,
        ?string $confirmToken,
    ): self {
        $user = new self($id, $date, $name);
        $user->email = $email;
        $user->passwordHash = $passwordHash;
        $user->confirmToken = $confirmToken;
        $user->status = self::STATUS_WAIT;

        return $user;
    }

    public function confirmSignUp(): void
    {
        if (!$this->isWait()) {
            throw new DomainException('User is already confirmed.');
        }

        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }

    public function changeRole(Role $role): void
    {
        if ($this->role->isEqual($role)) {
            throw new DomainException('Role already assigned.');
        }

        $this->role = $role;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isNew(): bool
    {
        return $this->status === self::STATUS_NEW;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isBlocked(): bool
    {
        return $this->status === self::STATUS_BLOCK;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    public function getResetToken(): ResetToken
    {
        return $this->resetToken;
    }

    public function getNewEmail(): ?Email
    {
        return $this->newEmail;
    }

    public function getNewEmailToken(): ?string
    {
        return $this->newEmailToken;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function requestPasswordReset(ResetToken $token, DateTimeImmutable $date): void
    {
        if (!$this->email) {
            throw new DomainException('Email is not specified');
        }

        if ($this->resetToken && !$this->resetToken->isExpiredTo($date)) {
            throw new DomainException('Resetting token already requested.');
        }

        if (!$this->isActive()) {
            throw new DomainException('User is not active.');
        }

        $this->resetToken = $token;
    }

    public function passwordReset(DateTimeImmutable $date, string $hash): void
    {
        if (!$this->resetToken) {
            throw new DomainException('Resetting is not requested.');
        }

        if ($this->resetToken->isExpiredTo($date)) {
            throw new DomainException('Reset token is expired.');
        }

        $this->passwordHash = $hash;
    }

    public function requestEmailChanging(Email $email, string $token): void
    {
        if (!$this->isActive()) {
            throw new DomainException('User is not confirmed.');
        }

        if ($this->email && $this->email->isEqual($email)) {
            throw new DomainException('Email is already same.');
        }

        $this->newEmail = $email;
        $this->newEmailToken = $token;
    }

    public function confirmEmailChanging(string $token): void
    {
        if (!$this->newEmailToken) {
            throw new DomainException('Changing is not requested.');
        }

        if ($token !== $this->newEmailToken) {
            throw new DomainException('Invalid token.');
        }

        $this->email = $this->newEmail;
        $this->newEmail = null;
        $this->newEmailToken = null;
    }

    public function changeName(Name $name): void
    {
        $this->name = $name;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function edit(Email $email, Name $name): void
    {
        $this->email = $email;
        $this->name = $name;
    }

    public function block(): void
    {
        if ($this->isBlocked()) {
            throw new DomainException('User already blocked.');
        }

        $this->status = self::STATUS_BLOCK;
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new DomainException('User already active.');
        }

        $this->status = self::STATUS_ACTIVE;
    }

    public function getAvatar(): null
    {
        return null;
    }
}
