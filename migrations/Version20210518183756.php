<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210518183756 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE app_answer (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, value VARCHAR(255) NOT NULL, correct TINYINT(1) NOT NULL, INDEX IDX_3FDE27A51E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_question (id INT AUTO_INCREMENT NOT NULL, quiz_id INT NOT NULL, type VARCHAR(50) NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_BE7729E3853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_quiz (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, subject_id INT NOT NULL, valid_from DATETIME NOT NULL, valid_to DATETIME NOT NULL, code VARCHAR(30) NOT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, enabled TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_A13CDF3277153098 (code), INDEX IDX_A13CDF327E3C61F9 (owner_id), INDEX IDX_A13CDF3223EDC87 (subject_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_quiz_student_group (quiz_id INT NOT NULL, student_group_id INT NOT NULL, INDEX IDX_AF5F2431853CD175 (quiz_id), INDEX IDX_AF5F24314DDF95DC (student_group_id), PRIMARY KEY(quiz_id, student_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_response (id INT AUTO_INCREMENT NOT NULL, quiz_id INT NOT NULL, student_id INT NOT NULL, score INT DEFAULT NULL, start_date DATETIME DEFAULT NULL, finish_date DATETIME DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_36FB6B56D17F50A6 (uuid), INDEX IDX_36FB6B56853CD175 (quiz_id), INDEX IDX_36FB6B56CB944F1A (student_id), UNIQUE INDEX idx_student_quiz_uniq (student_id, quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_response_answer (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, response_id INT NOT NULL, score INT NOT NULL, INDEX IDX_EB7C48831E27F6BF (question_id), INDEX IDX_EB7C4883FBF32840 (response_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_response_quiz_answer (response_answer_id INT NOT NULL, answer_id INT NOT NULL, INDEX IDX_7C10282484305360 (response_answer_id), UNIQUE INDEX UNIQ_7C102824AA334807 (answer_id), PRIMARY KEY(response_answer_id, answer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_student_group (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7F8A00F477153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_subject (id INT AUTO_INCREMENT NOT NULL, supervisor_id INT NOT NULL, code VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1693BE3777153098 (code), INDEX IDX_1693BE3719E9AC5F (supervisor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_user (id INT AUTO_INCREMENT NOT NULL, group_id INT DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, username_canonical VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, encoder_name VARCHAR(255) DEFAULT NULL, last_login DATETIME DEFAULT NULL, password_reset_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, email_verification_token VARCHAR(255) DEFAULT NULL, verified_at DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, credentials_expire_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', email VARCHAR(255) DEFAULT NULL, email_canonical VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, locale_code VARCHAR(12) NOT NULL, INDEX IDX_88BDF3E9FE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_user_role (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_85879996A76ED395 (user_id), INDEX IDX_85879996D60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_permission (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, tree_left INT NOT NULL, tree_right INT NOT NULL, tree_level INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_C5160A4E727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_role (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, security_roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', tree_left INT NOT NULL, tree_right INT NOT NULL, tree_level INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_8C606FE3727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_role_permission (role_id INT NOT NULL, permission_id INT NOT NULL, INDEX IDX_45CEE9B8D60322AC (role_id), INDEX IDX_45CEE9B8FED90CCA (permission_id), PRIMARY KEY(role_id, permission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_answer ADD CONSTRAINT FK_3FDE27A51E27F6BF FOREIGN KEY (question_id) REFERENCES app_question (id)');
        $this->addSql('ALTER TABLE app_question ADD CONSTRAINT FK_BE7729E3853CD175 FOREIGN KEY (quiz_id) REFERENCES app_quiz (id)');
        $this->addSql('ALTER TABLE app_quiz ADD CONSTRAINT FK_A13CDF327E3C61F9 FOREIGN KEY (owner_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE app_quiz ADD CONSTRAINT FK_A13CDF3223EDC87 FOREIGN KEY (subject_id) REFERENCES app_subject (id)');
        $this->addSql('ALTER TABLE app_quiz_student_group ADD CONSTRAINT FK_AF5F2431853CD175 FOREIGN KEY (quiz_id) REFERENCES app_quiz (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_quiz_student_group ADD CONSTRAINT FK_AF5F24314DDF95DC FOREIGN KEY (student_group_id) REFERENCES app_student_group (id)');
        $this->addSql('ALTER TABLE app_response ADD CONSTRAINT FK_36FB6B56853CD175 FOREIGN KEY (quiz_id) REFERENCES app_quiz (id)');
        $this->addSql('ALTER TABLE app_response ADD CONSTRAINT FK_36FB6B56CB944F1A FOREIGN KEY (student_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE app_response_answer ADD CONSTRAINT FK_EB7C48831E27F6BF FOREIGN KEY (question_id) REFERENCES app_question (id)');
        $this->addSql('ALTER TABLE app_response_answer ADD CONSTRAINT FK_EB7C4883FBF32840 FOREIGN KEY (response_id) REFERENCES app_response (id)');
        $this->addSql('ALTER TABLE app_response_quiz_answer ADD CONSTRAINT FK_7C10282484305360 FOREIGN KEY (response_answer_id) REFERENCES app_response_answer (id)');
        $this->addSql('ALTER TABLE app_response_quiz_answer ADD CONSTRAINT FK_7C102824AA334807 FOREIGN KEY (answer_id) REFERENCES app_answer (id)');
        $this->addSql('ALTER TABLE app_subject ADD CONSTRAINT FK_1693BE3719E9AC5F FOREIGN KEY (supervisor_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE app_user ADD CONSTRAINT FK_88BDF3E9FE54D947 FOREIGN KEY (group_id) REFERENCES app_student_group (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE app_user_role ADD CONSTRAINT FK_85879996A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE app_user_role ADD CONSTRAINT FK_85879996D60322AC FOREIGN KEY (role_id) REFERENCES sylius_role (id)');
        $this->addSql('ALTER TABLE sylius_permission ADD CONSTRAINT FK_C5160A4E727ACA70 FOREIGN KEY (parent_id) REFERENCES sylius_permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_role ADD CONSTRAINT FK_8C606FE3727ACA70 FOREIGN KEY (parent_id) REFERENCES sylius_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_role_permission ADD CONSTRAINT FK_45CEE9B8D60322AC FOREIGN KEY (role_id) REFERENCES sylius_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_role_permission ADD CONSTRAINT FK_45CEE9B8FED90CCA FOREIGN KEY (permission_id) REFERENCES sylius_permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_user_oauth DROP FOREIGN KEY FK_C3471B78A76ED395');
        $this->addSql('ALTER TABLE sylius_user_oauth ADD CONSTRAINT FK_C3471B78A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE app_response_quiz_answer DROP FOREIGN KEY FK_7C102824AA334807');
        $this->addSql('ALTER TABLE app_answer DROP FOREIGN KEY FK_3FDE27A51E27F6BF');
        $this->addSql('ALTER TABLE app_response_answer DROP FOREIGN KEY FK_EB7C48831E27F6BF');
        $this->addSql('ALTER TABLE app_question DROP FOREIGN KEY FK_BE7729E3853CD175');
        $this->addSql('ALTER TABLE app_quiz_student_group DROP FOREIGN KEY FK_AF5F2431853CD175');
        $this->addSql('ALTER TABLE app_response DROP FOREIGN KEY FK_36FB6B56853CD175');
        $this->addSql('ALTER TABLE app_response_answer DROP FOREIGN KEY FK_EB7C4883FBF32840');
        $this->addSql('ALTER TABLE app_response_quiz_answer DROP FOREIGN KEY FK_7C10282484305360');
        $this->addSql('ALTER TABLE app_quiz_student_group DROP FOREIGN KEY FK_AF5F24314DDF95DC');
        $this->addSql('ALTER TABLE app_user DROP FOREIGN KEY FK_88BDF3E9FE54D947');
        $this->addSql('ALTER TABLE app_quiz DROP FOREIGN KEY FK_A13CDF3223EDC87');
        $this->addSql('ALTER TABLE app_quiz DROP FOREIGN KEY FK_A13CDF327E3C61F9');
        $this->addSql('ALTER TABLE app_response DROP FOREIGN KEY FK_36FB6B56CB944F1A');
        $this->addSql('ALTER TABLE app_subject DROP FOREIGN KEY FK_1693BE3719E9AC5F');
        $this->addSql('ALTER TABLE app_user_role DROP FOREIGN KEY FK_85879996A76ED395');
        $this->addSql('ALTER TABLE sylius_user_oauth DROP FOREIGN KEY FK_C3471B78A76ED395');
        $this->addSql('ALTER TABLE sylius_permission DROP FOREIGN KEY FK_C5160A4E727ACA70');
        $this->addSql('ALTER TABLE sylius_role_permission DROP FOREIGN KEY FK_45CEE9B8FED90CCA');
        $this->addSql('ALTER TABLE app_user_role DROP FOREIGN KEY FK_85879996D60322AC');
        $this->addSql('ALTER TABLE sylius_role DROP FOREIGN KEY FK_8C606FE3727ACA70');
        $this->addSql('ALTER TABLE sylius_role_permission DROP FOREIGN KEY FK_45CEE9B8D60322AC');
        $this->addSql('CREATE TABLE platform_admin_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, username_canonical VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, encoder_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, last_login DATETIME DEFAULT NULL, password_reset_token VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, password_requested_at DATETIME DEFAULT NULL, email_verification_token VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, verified_at DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, credentials_expire_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', email VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, email_canonical VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, last_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, locale_code VARCHAR(12) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE app_answer');
        $this->addSql('DROP TABLE app_question');
        $this->addSql('DROP TABLE app_quiz');
        $this->addSql('DROP TABLE app_quiz_student_group');
        $this->addSql('DROP TABLE app_response');
        $this->addSql('DROP TABLE app_response_answer');
        $this->addSql('DROP TABLE app_response_quiz_answer');
        $this->addSql('DROP TABLE app_student_group');
        $this->addSql('DROP TABLE app_subject');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('DROP TABLE app_user_role');
        $this->addSql('DROP TABLE sylius_permission');
        $this->addSql('DROP TABLE sylius_role');
        $this->addSql('DROP TABLE sylius_role_permission');
        $this->addSql('ALTER TABLE sylius_user_oauth DROP FOREIGN KEY FK_C3471B78A76ED395');
        $this->addSql('ALTER TABLE sylius_user_oauth ADD CONSTRAINT FK_C3471B78A76ED395 FOREIGN KEY (user_id) REFERENCES platform_admin_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
