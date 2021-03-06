<?php

namespace DevGroup\Multilingual\models;

use DevGroup\Multilingual\traits\FileActiveRecord;
use Yii;
use yii\data\ActiveDataProvider;
use yii2tech\filedb\ActiveRecord;

/**
 * Class CountryLanguage
 *
 * @property integer $id
 * @property string $name
 * @property string $name_native
 * @property string $iso_3166_1_alpha_2
 * @property string $iso_3166_1_alpha_3
 */
class CountryLanguage extends ActiveRecord implements CountryLanguageInterface
{
    use FileActiveRecord;

    public function rules()
    {
        return [
            [['id'], 'integer', 'on' => ['search']],
            [['name', 'iso_3166_1_alpha_2', 'iso_3166_1_alpha_3'], 'required', 'except' => ['search']],
            [['name', 'name_native'], 'string'],
            [['iso_3166_1_alpha_2'], 'string', 'max' => 2],
            [['iso_3166_1_alpha_3'], 'string', 'max' => 3],
        ];
    }

    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }

    public function search($params = [])
    {
        $query = static::find();
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
            ]
        );
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_native', $this->name_native])
            ->andFilterWhere(['iso_3166_1_alpha_2' => $this->iso_3166_1_alpha_2])
            ->andFilterWhere(['iso_3166_1_alpha_3' => $this->iso_3166_1_alpha_3]);
        return $dataProvider;
    }
}
