<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210425141026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add relationship with groups';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'CREATE TABLE app_quiz_student_group (quiz_id INT NOT NULL, student_group_id INT NOT NULL, INDEX IDX_AF5F2431853CD175 (quiz_id), INDEX IDX_AF5F24314DDF95DC (student_group_id), PRIMARY KEY(quiz_id, student_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE app_quiz_student_group ADD CONSTRAINT FK_AF5F2431853CD175 FOREIGN KEY (quiz_id) REFERENCES app_quiz (id) ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE app_quiz_student_group ADD CONSTRAINT FK_AF5F24314DDF95DC FOREIGN KEY (student_group_id) REFERENCES app_student_group (id)'
        );
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE app_quiz_student_group');
    }
}
