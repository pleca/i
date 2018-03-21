<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180315210318 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE intra_document_category (id INT AUTO_INCREMENT NOT NULL, parent_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intra_documents (document_id INT AUTO_INCREMENT NOT NULL, document_category_id INT DEFAULT NULL, document_file VARCHAR(255) NOT NULL, document_file_title VARCHAR(255) NOT NULL, document_date_add DATETIME NOT NULL, document_date_mod DATETIME NOT NULL, document_type VARCHAR(127) NOT NULL, document_desc LONGTEXT NOT NULL, document_creator_id INT UNSIGNED NOT NULL, document_user_id INT UNSIGNED NOT NULL, INDEX IDX_8B1970A090EFAA88 (document_category_id), PRIMARY KEY(document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE intra_documents ADD CONSTRAINT FK_8B1970A090EFAA88 FOREIGN KEY (document_category_id) REFERENCES intra_document_category (id)');
        $this->addSql('ALTER TABLE intra_user CHANGE user_vacation_days user_vacation_days INT DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE intra_documents DROP FOREIGN KEY FK_8B1970A090EFAA88');
        $this->addSql('DROP TABLE intra_document_category');
        $this->addSql('DROP TABLE intra_documents');
        $this->addSql('ALTER TABLE intra_user CHANGE user_vacation_days user_vacation_days INT DEFAULT NULL');
    }
}
