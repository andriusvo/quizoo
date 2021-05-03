<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210503170225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add UUID for Responses';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE app_quiz RENAME INDEX idx_a13cdf32a76ed395 TO IDX_A13CDF327E3C61F9');
        $this->addSql('ALTER TABLE app_response ADD uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_36FB6B56D17F50A6 ON app_response (uuid)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE app_quiz RENAME INDEX idx_a13cdf327e3c61f9 TO IDX_A13CDF32A76ED395');
        $this->addSql('DROP INDEX UNIQ_36FB6B56D17F50A6 ON app_response');
        $this->addSql('ALTER TABLE app_response DROP uuid');
    }
}
