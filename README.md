# answersAsReadonly : Allow to set some answer part to read only. #

This add a new attribute (advanced setting) in each question to set answers part as “read only”. 

Work in progress : 
- All question type with all settings didn't work
- Control is done only via html and javascript, no PHP control is done.
- You can not use `self` inside Expression.

Plugin are tested in LimeSurvey 3 and can be tested in LimeSurvey 4.

## Installation

### Via GIT
- Go to your LimeSurvey Directory
- Clone in plugins/answersAsReadonly directory : `git clone https://gitlab.com/SondagesPro/answersAsReadonly.git answersAsReadonly`

### Via ZIP dowload
- Get the file [answersAsReadonly.zip](https://dl.sondages.pro/answersAsReadonly.zip)
- Extract : `unzip answersAsReadonly.zip`
- Move the directory to plugins/ directory inside LimeSurvey

## Contribute

Contribution are welcome, for patch and issue : use [gitlab](https://gitlab.com/SondagesPro/answersAsReadonly).

## Home page & Copyright
- HomePage <http://extensions.sondages.pro/>
- Copyright © 2018-2019 Denis Chenu <http://sondages.pro> and [contributors](https://gitlab.com/SondagesPro/answersAsReadonly/graphs/master).
- Licence : GNU Affero General Public License <https://www.gnu.org/licenses/agpl-3.0.html>

## Changelog
- 2020-03-09 _0.3.0_ : Allow usage of in page question : force expression as static.
- 2019-11-09 _0.2.0_ : Show uploaded files and allow dowload
- 2018-05-21 _0.0.4_ : fix upload
- 2018-05-21 _0.0.4_ : fix buttons and css
- 2018-05-21 _0.0.3_ : fix HTML validity
- 2018-05-21 _0.0.2_ : fix for dropdown
- 2018-05-20 _0.0.1_ : initial realease
