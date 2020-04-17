<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200417134840 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE configuration_template (id INT AUTO_INCREMENT NOT NULL, configuration_id INT DEFAULT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_F685A89873F32DD8 (configuration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_enabled TINYINT(1) NOT NULL, password_request_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE configuration (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expense (id INT AUTO_INCREMENT NOT NULL, recurring_payment_id INT NOT NULL, due_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, is_resolved TINYINT(1) NOT NULL, INDEX IDX_2D3A8DA6B2CFAA1A (recurring_payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grocery_item (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, source VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, is_deleted TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8F9EDB8A3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE configuration_item (id INT AUTO_INCREMENT NOT NULL, configuration_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, default_value VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, input_type VARCHAR(255) NOT NULL, output_format VARCHAR(255) NOT NULL, INDEX IDX_7A48531973F32DD8 (configuration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE measurement (id INT AUTO_INCREMENT NOT NULL, device_id INT NOT NULL, name VARCHAR(255) NOT NULL, value INT NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, INDEX IDX_2CE0D81194A4C7D4 (device_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proxy_server (id INT AUTO_INCREMENT NOT NULL, host VARCHAR(255) NOT NULL, port VARCHAR(255) NOT NULL, attempts INT NOT NULL, is_blacklisted TINYINT(1) NOT NULL, blacklisted_at DATETIME DEFAULT NULL, is_whitelisted TINYINT(1) NOT NULL, whitelisted_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE device (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, configuration_id INT NOT NULL, name VARCHAR(255) NOT NULL, device_type VARCHAR(255) NOT NULL, device_id VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, INDEX IDX_92FB68E54177093 (room_id), UNIQUE INDEX UNIQ_92FB68E73F32DD8 (configuration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shopping_list_item (id INT AUTO_INCREMENT NOT NULL, grocery_item_id INT NOT NULL, quantity INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, is_resolved TINYINT(1) NOT NULL, INDEX IDX_4FB1C224FF98F97E (grocery_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recurring_payment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, period VARCHAR(255) NOT NULL, activation_time DATETIME NOT NULL, is_automated TINYINT(1) NOT NULL, payment_tag VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, is_deleted TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, filename VARCHAR(255) NOT NULL, uuid VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE configuration_template_item (id INT AUTO_INCREMENT NOT NULL, configuration_item_id INT NOT NULL, template_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_8E8F976E9C279A80 (configuration_item_id), INDEX IDX_8E8F976E5DA0FB8 (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE configuration_template ADD CONSTRAINT FK_F685A89873F32DD8 FOREIGN KEY (configuration_id) REFERENCES configuration (id)');
        $this->addSql('ALTER TABLE expense ADD CONSTRAINT FK_2D3A8DA6B2CFAA1A FOREIGN KEY (recurring_payment_id) REFERENCES recurring_payment (id)');
        $this->addSql('ALTER TABLE grocery_item ADD CONSTRAINT FK_8F9EDB8A3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE configuration_item ADD CONSTRAINT FK_7A48531973F32DD8 FOREIGN KEY (configuration_id) REFERENCES configuration (id)');
        $this->addSql('ALTER TABLE measurement ADD CONSTRAINT FK_2CE0D81194A4C7D4 FOREIGN KEY (device_id) REFERENCES device (id)');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68E54177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68E73F32DD8 FOREIGN KEY (configuration_id) REFERENCES configuration (id)');
        $this->addSql('ALTER TABLE shopping_list_item ADD CONSTRAINT FK_4FB1C224FF98F97E FOREIGN KEY (grocery_item_id) REFERENCES grocery_item (id)');
        $this->addSql('ALTER TABLE configuration_template_item ADD CONSTRAINT FK_8E8F976E9C279A80 FOREIGN KEY (configuration_item_id) REFERENCES configuration_item (id)');
        $this->addSql('ALTER TABLE configuration_template_item ADD CONSTRAINT FK_8E8F976E5DA0FB8 FOREIGN KEY (template_id) REFERENCES configuration_template (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE configuration_template_item DROP FOREIGN KEY FK_8E8F976E5DA0FB8');
        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68E54177093');
        $this->addSql('ALTER TABLE configuration_template DROP FOREIGN KEY FK_F685A89873F32DD8');
        $this->addSql('ALTER TABLE configuration_item DROP FOREIGN KEY FK_7A48531973F32DD8');
        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68E73F32DD8');
        $this->addSql('ALTER TABLE shopping_list_item DROP FOREIGN KEY FK_4FB1C224FF98F97E');
        $this->addSql('ALTER TABLE configuration_template_item DROP FOREIGN KEY FK_8E8F976E9C279A80');
        $this->addSql('ALTER TABLE measurement DROP FOREIGN KEY FK_2CE0D81194A4C7D4');
        $this->addSql('ALTER TABLE expense DROP FOREIGN KEY FK_2D3A8DA6B2CFAA1A');
        $this->addSql('ALTER TABLE grocery_item DROP FOREIGN KEY FK_8F9EDB8A3DA5256D');
        $this->addSql('DROP TABLE configuration_template');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE configuration');
        $this->addSql('DROP TABLE expense');
        $this->addSql('DROP TABLE grocery_item');
        $this->addSql('DROP TABLE configuration_item');
        $this->addSql('DROP TABLE measurement');
        $this->addSql('DROP TABLE proxy_server');
        $this->addSql('DROP TABLE device');
        $this->addSql('DROP TABLE shopping_list_item');
        $this->addSql('DROP TABLE recurring_payment');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE configuration_template_item');
    }
}
