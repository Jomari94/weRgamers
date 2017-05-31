<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Group;

/**
 * GroupSearch represents the model behind the search form about `app\models\Group`.
 */
class GroupSearch extends Group
{
    /**
     * @var bool $myGroups Indica si hay que mostrar sólo los grupos del usuario
     */
    public $myGroups;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_game', 'id_platform'], 'integer'],
            [['name', 'game_name', 'gameName', 'myGroups'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'game_name' => Yii::t('app', 'Game'),
            'myGroups' => Yii::t('app', 'My groups'),
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
        $query = Group::find();
        $query->joinWith('gameName');
        $subQuery = Member::find()
            ->select('id_group, count(*) as total_members')
            ->where(['accepted' => true])
            ->groupBy('id_group');
        $query->leftJoin(['members_amount' => $subQuery], 'members_amount.id_group = groups.id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'groups.name' => [
                    'asc' => ['groups.name' => SORT_ASC,],
                    'desc' => ['groups.name' => SORT_DESC,],
                    'label' => Yii::t('app', 'Name'),
                    'default' => SORT_ASC
                ],
                'totalMembers' => [
                    'asc' => ['members_amount.total_members' => SORT_ASC],
                    'desc' => ['members_amount.total_members' => SORT_DESC],
                    'label' => Yii::t('app', 'Members'),
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
            'id_game' => $this->id_game,
            'id_platform' => $this->id_platform,
        ]);

        $query->andFilterWhere(['like', 'groups.name', $this->name])
            ->andFilterWhere(['like', 'games.name', $this->game_name]);
        if ($this->myGroups == "1") {
            $subQuery = Member::find()
                ->select('id_group')
                ->where(['id_user' => Yii::$app->user->id, 'accepted' => true]);
            $query->andFilterWhere(['in', 'groups.id', $subQuery]);
        }

        return $dataProvider;
    }
}
