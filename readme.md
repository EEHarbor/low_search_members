# Low Search Members for ExpressionEngine

This add-on allows you to target standard member fields for searching with [Low Search](http://gotolow.com/addons/low-search). Search results are **channel entries** related to matching members, as though the [author_id parameter](https://ellislab.com/expressionengine/user-guide/add-ons/channel/channel_entries.html#author-id) was set. This is particularly useful when dealing with a member add-on like [Zoo Visitor](http://ee-zoo.com/add-ons/visitor/). Requires Low Search v3.4.0+.

## Installation

- Download and unzip;
- Copy the `low_search_members` folder to your `system/expressionengine/third_party` directory;
- All set!

## Usage

Once installed, you can use these parameters, either in the Results tag or as fields in your search form:

- `member:username`
- `member:screen_name`
- `member:email`
- `member:url`
- `member:location`
- `member:occupation`
- `member:interests`
- `member:bday_d`
- `member:bday_m`
- `member:bday_y`
- `member:aol_im`
- `member:yahoo_im`
- `member:msn_im`
- `member:icq`
- `member:bio`
- `member:signature`

To make these parameters behave like a [Field Search parameter](http://gotolow.com/addons/low-search/docs/filters#field-search), add `search:` after the `member:` prefix, eg. `member:search:username`. That will allow for partial matches.

## Examples

### Get entries for members that have a birthday in a given month

    <select name="member:bday_m">
        <option value="01">January</option>
        <option value="02">February</option>
        <option value="03">March</option>
        <option value="04">April</option>
        <option value="05">May</option>
        <option value="06">June</option>
        <option value="07">July</option>
        <option value="08">August</option>
        <option value="09">September</option>
        <option value="10">October</option>
        <option value="11">November</option>
        <option value="12">December</option>
    </select>

### Get entries for members that have a gmail.com email address

    <input name="member:search:email" value="@gmail.com">

### Get entries for member that have selected items as interests

    <input type="checkbox" name="member:search:interests[]" value="knitting"> Knitting
    <input type="checkbox" name="member:search:interests[]" value="embroidery"> Embroidery
    <input type="checkbox" name="member:search:interests[]" value="crochet"> Crochet