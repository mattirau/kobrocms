<?php

/**
 * Poll Module (tough question be asked!)
 * 
 * @director M. Night Shyamalan
 *
 */
class Module_Poll extends Module {

    protected function _default($params) {
        $view = new View();
        $view->page = $this->kobros->page;
        $view->error = false;

        $sql = "SELECT * FROM question WHERE id = ?";
        $stmt = $this->kobros->db->prepare($sql);
        $stmt->execute(array($params['question_id']));

        $question = $stmt->fetch(PDO::FETCH_OBJ);

        $sql = "SELECT * FROM answer WHERE question_id = ?";
        $stmt = $this->kobros->db->prepare($sql);
        $stmt->execute(array($question->id));

        $answers = array();
        while ($res = $stmt->fetch(PDO::FETCH_OBJ)) {
            $answers[] = $res;
        }

        // We put view
        $view = new View();
        $view->question = $question;
        $view->answers = $answers;
        $view->forward = $params['forward'];

        return $view->render(ROOT . '/app/templates/data/poll/default.phtml');
    }

    protected function _vote($params) {
        $sql = "UPDATE answer SET votes = votes + 1 WHERE question_id = ? AND id = ?";
        $stmt = $this->kobros->db->prepare($sql);
        $stmt->execute(array($params['question_id'], $params['answer_id']));

        $forward = $params['forward'];
        header("Location: {$forward}");
        die();
    }

    protected function _twist($params) {
        // Unexpected hard core plot twist be here! EASTER EGG!!!

        $sql = "SELECT * FROM answer";
        $q = $this->kobros->db->query($sql);

        $answers = array();
        while ($res = $q->fetch(PDO::FETCH_OBJ)) {
            $answers[] = $res;
        }

        foreach ($answers as $answer) {
            $votes = rand(0, 10000);
            $sql = "UPDATE answer SET votes = {} WHERE id = {}";
            $stmt = $this->kobros->db->prepare($sql);
            $stmt->execute(array($votes, $answer->id));
        }
    }

}
