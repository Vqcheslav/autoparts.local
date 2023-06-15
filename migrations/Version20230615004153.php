<?php


declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615004153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE autopart (autopart_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', car_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', warehouse_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', manufacturer_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) NOT NULL, description VARCHAR(2000) NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_24A98051C3C6F69F (car_id), INDEX IDX_24A980515080ECDE (warehouse_id), INDEX IDX_24A98051A23B42D (manufacturer_id), PRIMARY KEY(autopart_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car (car_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', manufacturer VARCHAR(255) NOT NULL, brand VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, generation VARCHAR(255) NOT NULL, engine VARCHAR(255) NOT NULL, year SMALLINT NOT NULL, PRIMARY KEY(car_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manufacturer (manufacturer_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, country VARCHAR(2) NOT NULL, PRIMARY KEY(manufacturer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (order_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', autopart_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', amount SMALLINT NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), INDEX IDX_F52993989FB60294 (autopart_id), PRIMARY KEY(order_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE warehouse (warehouse_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', address VARCHAR(255) NOT NULL, opening_hours VARCHAR(255) NOT NULL, working_days VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, PRIMARY KEY(warehouse_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE autopart ADD CONSTRAINT FK_24A98051C3C6F69F FOREIGN KEY (car_id) REFERENCES car (car_id)');
        $this->addSql('ALTER TABLE autopart ADD CONSTRAINT FK_24A980515080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouse (warehouse_id)');
        $this->addSql('ALTER TABLE autopart ADD CONSTRAINT FK_24A98051A23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (manufacturer_id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (user_id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993989FB60294 FOREIGN KEY (autopart_id) REFERENCES autopart (autopart_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE autopart DROP FOREIGN KEY FK_24A98051C3C6F69F');
        $this->addSql('ALTER TABLE autopart DROP FOREIGN KEY FK_24A980515080ECDE');
        $this->addSql('ALTER TABLE autopart DROP FOREIGN KEY FK_24A98051A23B42D');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993989FB60294');
        $this->addSql('DROP TABLE autopart');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE manufacturer');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE warehouse');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
