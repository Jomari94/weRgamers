<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Game;

/**
 * GameSearch represents the model behind the search form about `app\models\Game`.
 */
class GameSearch extends Game
{
    /**
     * @var array Nombre de las plataformas del juego
     */
    public $namePlatforms = '';

    /**
     * Reglas del modelo.
     * @return array
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'genre', 'released', 'developers', 'namePlatforms'], 'safe'],
        ];
    }

    /**
     * Escenarios del modelo.
     * @return array
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
        $query = Game::find();
        $query->joinWith('platforms');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'name',
                'genre',
                'released',
                'developers',
                'namePlatforms' => [
                    'asc' => ['platforms.name' => SORT_ASC],
                    'desc' => ['platforms.name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
            ]
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
            'released' => $this->released,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'genre', $this->genre])
            ->andFilterWhere(['like', 'developers', $this->developers]);

        $query->joinWith(['platforms' => function ($q) {
            $q->where(['or like', 'platforms.name', $this->namePlatforms]);
        }]);
        return $dataProvider;
    }
}
