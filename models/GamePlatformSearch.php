<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\GamePlatform;

/**
 * GamePlatformSearch represents the model behind the search form about `app\models\GamePlatform`.
 */
class GamePlatformSearch extends GamePlatform
{
    /**
     * Nombre del juego
     * @var string
     */
    public $name;
    public $genre;
    public $released;
    public $developers;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_game', 'id_platform'], 'integer'],
            [['name', 'genre', 'released', 'developers'], 'safe'],
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
        $query = GamePlatform::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id_game',
                'id_platform',
                'name' => [
                    'asc' => ['games.name' => SORT_ASC,],
                    'desc' => ['games.name' => SORT_DESC,],
                    'label' => Yii::t('app', 'Name'),
                    'default' => SORT_ASC
                ],
                'genre' => [
                    'asc' => ['games.genre' => SORT_ASC,],
                    'desc' => ['games.genre' => SORT_DESC,],
                    'label' => Yii::t('app', 'Genre'),
                    'default' => SORT_ASC
                ],
                'released' => [
                    'asc' => ['games.released' => SORT_ASC,],
                    'desc' => ['games.released' => SORT_DESC,],
                    'label' => Yii::t('app', 'Released'),
                    'default' => SORT_ASC
                ],
                'developers' => [
                    'asc' => ['games.developers' => SORT_ASC,],
                    'desc' => ['games.developers' => SORT_DESC,],
                    'label' => Yii::t('app', 'Developers'),
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
            'id_game' => $this->id_game,
            'id_platform' => $this->id_platform,
        ]);

        $query->joinWith(['game' => function ($q) {
            $q->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'genre', $this->genre])
            ->andFilterWhere(['released' => $this->released])
            ->andFilterWhere(['ilike', 'developers', $this->developers]);
        }]);

        return $dataProvider;
    }
}
