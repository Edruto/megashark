<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Rooms Controller
 *
 *
 * @method \App\Model\Entity\Room[] paginate($object = null, array $settings = [])
 */
class RoomsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $rooms = $this->paginate($this->Rooms);

        $this->set(compact('rooms'));
        $this->set('_serialize', ['rooms']);
    }

    /**
     * View method
     *
     * @param string|null $id Room id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $room = $this->Rooms->get($id, [
            'contain' => ['Showtimes']]);
            
        $lundi = strtotime('Monday this week');
        $mardi =strtotime('+1day Monday this week');
        $mercredi =strtotime('+2days Monday this week');
        $jeudi =strtotime('+3days Monday this week');
        $vendredi =strtotime('+4days Monday this week');
        $samedi =strtotime('+5days Monday this week');
        $dimanche =strtotime('+6days Monday this week');
        $lundisuivant = strtotime('+7days Monday this week');
        
        $lun = TableRegistry::get('Showtimes')->find()
            ->where(['room_id ='=> $id])
            ->where(['start >=' => $lundi])
            ->where(['start <'=> $mardi]);
        
        
        $mar = TableRegistry::get('Showtimes')->find()
            ->where(['room_id ='=> $id])
            ->where(['start >=' => $mardi])
            ->where(['start <'=> $mercredi]);
        
        $mer = TableRegistry::get('Showtimes')->find()
            ->where(['room_id ='=> $id])
            ->where(['start >=' => $mercredi])
            ->where(['start <'=> $jeudi]);
            
        $jeu = TableRegistry::get('Showtimes')->find()
            ->where(['room_id ='=> $id])
            ->where(['start >=' => $jeudi])
            ->where(['start <'=> $vendredi]);
        
        $ven = TableRegistry::get('Showtimes')->find()
            ->where(['room_id ='=> $id])
            ->where(['start >=' => $vendredi])
            ->where(['start <'=> $samedi]);
        
        $sam = TableRegistry::get('Showtimes')->find()
            ->where(['room_id ='=> $id])
            ->where(['start >=' => $samedi])
            ->where(['start <'=> $dimanche]);
            
        $dim = TableRegistry::get('Showtimes')->find()
            ->where(['room_id ='=> $id])
            ->where(['start >=' => $dimanche])
            ->where(['start <'=> $lundisuivant]);
    
        $query = TableRegistry::get('Showtimes')->find()
            ->where(['room_id ='=> $id])
            ->where(['start >=' => $lundi])
            ->where(['start <='=> $lundisuivant]);
             
       
        $this->set('semaine',[0=> $lun, 1=> $mar, 2=> $mer, 3=> $jeu, 4=> $ven, 5=> $sam, 6=> $dim]);
                    
        $this->set('jours',[0=> "Lundi, ".date('d-m-Y', $lundi), 1=> "Mardi, ".date('d-m-Y', $mardi), 2=> "Mercredi, ".date('d-m-Y', $mercredi), 3=> "Jeudi, ".date('d-m-Y', $jeudi), 4=> "Vendredi, ".date('d-m-Y', $vendredi), 5=> "Samedi, ".date('d-m-Y', $samedi), 6=> "Dimanche, ".date('d-m-Y', $dimanche)]);
                                
        $this->set('seances',$query);
        $this->set('room', $room);
        $this->set('_serialize', ['room']); 
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $room = $this->Rooms->newEntity();
        if ($this->request->is('post')) {
            $room = $this->Rooms->patchEntity($room, $this->request->getData());
            if ($this->Rooms->save($room)) {
                $this->Flash->success(__('The room has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The room could not be saved. Please, try again.'));
        }
        $this->set(compact('room'));
        $this->set('_serialize', ['room']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Room id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $room = $this->Rooms->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $room = $this->Rooms->patchEntity($room, $this->request->getData());
            if ($this->Rooms->save($room)) {
                $this->Flash->success(__('The room has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The room could not be saved. Please, try again.'));
        }
        $this->set(compact('room'));
        $this->set('_serialize', ['room']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Room id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $room = $this->Rooms->get($id);
        if ($this->Rooms->delete($room)) {
            $this->Flash->success(__('The room has been deleted.'));
        } else {
            $this->Flash->error(__('The room could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
