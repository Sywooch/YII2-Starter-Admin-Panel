<?php

namespace common\models\search;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * AppUserSearch represents the model behind the search form about `common\models\User`.
 */

class AppUserSearch extends User
{
    public $q;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['id','first_name','last_name', 'email','title','immidiate_supervisior','q'], 'safe'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = AppUserSearch::find()->joinWith('clientProfile')->where(['=','client_id', $_GET['id']])->andWhere(['=','role',CLIENT_APP_USER])->andWhere(['=','user.is_delete',NOT_DELETED])->orderBy(['(id)' => SORT_DESC]);
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name]);


        return $dataProvider;
    }

    //Search
    public function SearchUser($params)
    {
        $query = AppUserSearch::find()->joinWith('clientProfile')->andWhere(['=','role',CLIENT_APP_USER])->orderBy(['(id)' => SORT_DESC]);

        // add conditions that should always apply here
        $dataProvider = new \sjaakp\alphapager\ActiveDataProvider([
            'query' => $query,
            'alphaAttribute' => 'full_name'
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name]);

        $query->orFilterWhere(['OR like', 'app_user_profile.email', $this->q])
            ->orFilterWhere(['OR like', 'full_name', $this->q]);

        return $dataProvider;
    }
}
?>