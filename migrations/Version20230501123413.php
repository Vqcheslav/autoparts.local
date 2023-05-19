<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230501123413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE autopart (autopart_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) NOT NULL, description VARCHAR(2000) NOT NULL, PRIMARY KEY(autopart_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (order_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', autopart_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', amount SMALLINT NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), INDEX IDX_F52993989FB60294 (autopart_id), PRIMARY KEY(order_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (user_id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993989FB60294 FOREIGN KEY (autopart_id) REFERENCES autopart (autopart_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993989FB60294');
        $this->addSql('DROP TABLE autopart');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
