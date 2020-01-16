<?php
use Migrations\AbstractMigration;

class RenameSimpleDetail1To2 extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('simple_detail1');
        $table
            ->rename('simple_detail2')
            ->save();
    }
}
