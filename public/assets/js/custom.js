if (!Date.prototype.toFormat) {
  (function() {

    function pad(number) {
      if (number < 10) {
        return '0' + number;
      }
      return number;
    }

    /**
     * @param format Date
     * @return string
     */
    Date.prototype.toFormat = function(format) {

      let _return = false;

      if (this != 'Invalid Date') {

        _return = true;
        format = format.toUpperCase();

        // [YYYY] getFullYear
        format = format.replace('YYYY', pad(this.getFullYear()));
        // [MM] getMonth +1
        format = format.replace('MM', pad(this.getMonth()+1));
        // [MM] getDate
        format = format.replace('DD', pad(this.getDate()));
      }

      return _return ? format : '';
    };

  })();
}