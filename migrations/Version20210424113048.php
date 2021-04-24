<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210424113048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add subject & student group entities';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'CREATE TABLE app_subject (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, code VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1693BE3777153098 (code), INDEX IDX_1693BE37A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE app_subject ADD CONSTRAINT FK_1693BE37A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id)'
        );
        $this->addSql('ALTER TABLE app_quiz ADD subject_id INT NOT NULL, DROP subject');
        $this->addSql(
            'ALTER TABLE app_quiz ADD CONSTRAINT FK_A13CDF3223EDC87 FOREIGN KEY (subject_id) REFERENCES app_subject (id)'
        );
        $this->addSql('CREATE INDEX IDX_A13CDF3223EDC87 ON app_quiz (subject_id)');
        $this->addSql('ALTER TABLE app_student_group CHANGE title code VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7F8A00F477153098 ON app_student_group (code)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE app_quiz DROP FOREIGN KEY FK_A13CDF3223EDC87');
        $this->addSql('DROP TABLE app_subject');
        $this->addSql('DROP INDEX IDX_A13CDF3223EDC87 ON app_quiz');
        $this->addSql(
            'ALTER TABLE app_quiz ADD subject VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP subject_id'
        );
        $this->addSql('DROP INDEX UNIQ_7F8A00F477153098 ON app_student_group');
        $this->addSql(
            'ALTER TABLE app_student_group CHANGE code title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci'
        );
    }
}
