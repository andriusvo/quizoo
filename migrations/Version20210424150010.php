<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210424150010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Changed constraint';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE app_user DROP FOREIGN KEY FK_88BDF3E9FE54D947');
        $this->addSql(
            'ALTER TABLE app_user ADD CONSTRAINT FK_88BDF3E9FE54D947 FOREIGN KEY (group_id) REFERENCES app_student_group (id) ON DELETE SET NULL'
        );
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE app_user DROP FOREIGN KEY FK_88BDF3E9FE54D947');
        $this->addSql(
            'ALTER TABLE app_user ADD CONSTRAINT FK_88BDF3E9FE54D947 FOREIGN KEY (group_id) REFERENCES app_student_group (id) ON UPDATE NO ACTION ON DELETE NO ACTION'
        );
    }
}
