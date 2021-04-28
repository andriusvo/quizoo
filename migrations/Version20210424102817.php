<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210424102817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add permissions & RBAC';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE app_quiz DROP FOREIGN KEY FK_A13CDF32A76ED395');
        $this->addSql('ALTER TABLE sylius_user_oauth DROP FOREIGN KEY FK_C3471B78A76ED395');
        $this->addSql(
            'CREATE TABLE app_student_group (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE app_user (id INT AUTO_INCREMENT NOT NULL, group_id INT DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, username_canonical VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, encoder_name VARCHAR(255) DEFAULT NULL, last_login DATETIME DEFAULT NULL, password_reset_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, email_verification_token VARCHAR(255) DEFAULT NULL, verified_at DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, credentials_expire_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', email VARCHAR(255) DEFAULT NULL, email_canonical VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, locale_code VARCHAR(12) NOT NULL, INDEX IDX_88BDF3E9FE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE app_user_role (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_85879996A76ED395 (user_id), INDEX IDX_85879996D60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE sylius_permission (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, tree_left INT NOT NULL, tree_right INT NOT NULL, tree_level INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_C5160A4E727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE sylius_role (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, security_roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', tree_left INT NOT NULL, tree_right INT NOT NULL, tree_level INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_8C606FE3727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE sylius_role_permission (role_id INT NOT NULL, permission_id INT NOT NULL, INDEX IDX_45CEE9B8D60322AC (role_id), INDEX IDX_45CEE9B8FED90CCA (permission_id), PRIMARY KEY(role_id, permission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE app_user ADD CONSTRAINT FK_88BDF3E9FE54D947 FOREIGN KEY (group_id) REFERENCES app_student_group (id)'
        );
        $this->addSql(
            'ALTER TABLE app_user_role ADD CONSTRAINT FK_85879996A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id) ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE app_user_role ADD CONSTRAINT FK_85879996D60322AC FOREIGN KEY (role_id) REFERENCES sylius_role (id)'
        );
        $this->addSql(
            'ALTER TABLE sylius_permission ADD CONSTRAINT FK_C5160A4E727ACA70 FOREIGN KEY (parent_id) REFERENCES sylius_permission (id) ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE sylius_role ADD CONSTRAINT FK_8C606FE3727ACA70 FOREIGN KEY (parent_id) REFERENCES sylius_role (id) ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE sylius_role_permission ADD CONSTRAINT FK_45CEE9B8D60322AC FOREIGN KEY (role_id) REFERENCES sylius_role (id) ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE sylius_role_permission ADD CONSTRAINT FK_45CEE9B8FED90CCA FOREIGN KEY (permission_id) REFERENCES sylius_permission (id) ON DELETE CASCADE'
        );
        $this->addSql('DROP TABLE platform_admin_user');
        $this->addSql(
            'ALTER TABLE app_quiz ADD CONSTRAINT FK_A13CDF32A76ED395 FOREIGN KEY (owner_id) REFERENCES app_user (id)'
        );
        $this->addSql(
            'ALTER TABLE sylius_user_oauth ADD CONSTRAINT FK_C3471B78A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id)'
        );
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE app_user DROP FOREIGN KEY FK_88BDF3E9FE54D947');
        $this->addSql('ALTER TABLE app_quiz DROP FOREIGN KEY FK_A13CDF32A76ED395');
        $this->addSql('ALTER TABLE app_user_role DROP FOREIGN KEY FK_85879996A76ED395');
        $this->addSql('ALTER TABLE sylius_user_oauth DROP FOREIGN KEY FK_C3471B78A76ED395');
        $this->addSql('ALTER TABLE sylius_permission DROP FOREIGN KEY FK_C5160A4E727ACA70');
        $this->addSql('ALTER TABLE sylius_role_permission DROP FOREIGN KEY FK_45CEE9B8FED90CCA');
        $this->addSql('ALTER TABLE app_user_role DROP FOREIGN KEY FK_85879996D60322AC');
        $this->addSql('ALTER TABLE sylius_role DROP FOREIGN KEY FK_8C606FE3727ACA70');
        $this->addSql('ALTER TABLE sylius_role_permission DROP FOREIGN KEY FK_45CEE9B8D60322AC');
        $this->addSql(
            'CREATE TABLE platform_admin_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, username_canonical VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, encoder_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, last_login DATETIME DEFAULT NULL, password_reset_token VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, password_requested_at DATETIME DEFAULT NULL, email_verification_token VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, verified_at DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, credentials_expire_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', email VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, email_canonical VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, last_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, locale_code VARCHAR(12) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' '
        );
        $this->addSql('DROP TABLE app_student_group');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('DROP TABLE app_user_role');
        $this->addSql('DROP TABLE sylius_permission');
        $this->addSql('DROP TABLE sylius_role');
        $this->addSql('DROP TABLE sylius_role_permission');
        $this->addSql('ALTER TABLE app_quiz DROP FOREIGN KEY FK_A13CDF32A76ED395');
        $this->addSql(
            'ALTER TABLE app_quiz ADD CONSTRAINT FK_A13CDF32A76ED395 FOREIGN KEY (owner_id) REFERENCES platform_admin_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION'
        );
        $this->addSql('ALTER TABLE sylius_user_oauth DROP FOREIGN KEY FK_C3471B78A76ED395');
        $this->addSql(
            'ALTER TABLE sylius_user_oauth ADD CONSTRAINT FK_C3471B78A76ED395 FOREIGN KEY (user_id) REFERENCES platform_admin_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION'
        );
    }
}
