<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220817063048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE completed DROP FOREIGN KEY FK_3AF85C6EFCDAEAAA');
        $this->addSql('DROP INDEX UNIQ_3AF85C6EFCDAEAAA ON completed');
        $this->addSql('ALTER TABLE completed DROP order_id_id');
        $this->addSql('ALTER TABLE `order` CHANGE user_id user_id INT DEFAULT NULL, CHANGE datetime datetime DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F46FCDAEAAA');
        $this->addSql('DROP INDEX IDX_ED896F46FCDAEAAA ON order_detail');
        $this->addSql('ALTER TABLE order_detail ADD orders_id INT DEFAULT NULL, DROP order_id_id');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F46CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_ED896F46CFFE9AD6 ON order_detail (orders_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE completed ADD order_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE completed ADD CONSTRAINT FK_3AF85C6EFCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3AF85C6EFCDAEAAA ON completed (order_id_id)');
        $this->addSql('ALTER TABLE `order` CHANGE user_id user_id INT NOT NULL, CHANGE datetime datetime DATETIME NOT NULL');
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F46CFFE9AD6');
        $this->addSql('DROP INDEX IDX_ED896F46CFFE9AD6 ON order_detail');
        $this->addSql('ALTER TABLE order_detail ADD order_id_id INT NOT NULL, DROP orders_id');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F46FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_ED896F46FCDAEAAA ON order_detail (order_id_id)');
    }
}
