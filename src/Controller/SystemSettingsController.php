<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * SystemSettings Controller
 *
 * @property \App\Model\Table\SystemSettingsTable $SystemSettings
 */
class SystemSettingsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->SystemSettings->find();
        $systemSettings = $this->paginate($query);

        $this->set(compact('systemSettings'));
    }

    /**
     * View method
     *
     * @param string|null $id System Setting id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $systemSetting = $this->SystemSettings->get($id, contain: []);
        $this->set(compact('systemSetting'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $systemSetting = $this->SystemSettings->newEmptyEntity();
        if ($this->request->is('post')) {
            $systemSetting = $this->SystemSettings->patchEntity($systemSetting, $this->request->getData());
            if ($this->SystemSettings->save($systemSetting)) {
                $this->Flash->success(__('The system setting has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The system setting could not be saved. Please, try again.'));
        }
        $this->set(compact('systemSetting'));
    }

    /**
     * Edit method
     *
     * @param string|null $id System Setting id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $systemSetting = $this->SystemSettings->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $systemSetting = $this->SystemSettings->patchEntity($systemSetting, $this->request->getData());
            if ($this->SystemSettings->save($systemSetting)) {
                $this->Flash->success(__('The system setting has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The system setting could not be saved. Please, try again.'));
        }
        $this->set(compact('systemSetting'));
    }

    /**
     * Delete method
     *
     * @param string|null $id System Setting id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $systemSetting = $this->SystemSettings->get($id);
        if ($this->SystemSettings->delete($systemSetting)) {
            $this->Flash->success(__('The system setting has been deleted.'));
        } else {
            $this->Flash->error(__('The system setting could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
