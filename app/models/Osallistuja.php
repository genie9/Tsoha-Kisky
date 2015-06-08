<?php

class Osallistuja extends BaseModel {

    public $kokous_id, $jasen_id;

    public function construct($attributes) {
        parent::__construct($attributes);
    }

    public function save($kokous_id, $jasen_id) {
        $query = DB::connection()->prepare('INSERT INTO Kokous_has_Jasen (kokous_id, jasen_id)
                VALUES (:kokous_id, :jasen_id)');

        foreach ($jasen_id as $key => $value) {
            if ($value == '') {
                continue;
            }
            $query->execute(array('kokous_id' => $kokous_id, 'jasen_id' => $value));
        }
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT kokous.pvm, jasen.nimi FROM kokous_has_jasen '
                . 'INNER JOIN jasen ON kokous_has_jasen.jasen_id=jasen.id '
                . 'INNER JOIN Kokous ON kokous_has_jasen.kokous_id=kokous.id');
        $query->execute();
        $rows = $query->fetchAll();
        $osallistujat = array();

        foreach ($rows as $row) {
            $osallistujat[] = new Osallistuja(array(
                'pvm' => $row['pvm'],
                'nimi' => $row['nimi']
            ));
        }
        return $osallistujat;
    }

}
