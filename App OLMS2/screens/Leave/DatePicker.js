import { Item, Text } from 'native-base';
import { StyleSheet, Image, Dimensions, View, TouchableOpacity, Platform } from "react-native";
import DateTimePicker from 'react-native-modal-datetime-picker';
import moment from 'moment';
import React, { Component } from 'react';

const deviceHeight = Dimensions.get("window").height;
const deviceWidth = Dimensions.get("window").width;

export default class DatePicker extends Component {

    constructor(props) {
        super(props);
        this.state = {
            isDateTimePickerVisible: false
        }
    }

    componentWillReceiveProps(nextProps) { this.setState({ date: nextProps.date }); }

    _showDateTimePicker = () => this.setState({ isDateTimePickerVisible: true });

    _hideDateTimePicker = () => this.setState({ isDateTimePickerVisible: false });

    _handleDatePicked = (value) => {
        this.props.handle(moment(value).format('YYYY-MM-DD'));
        this._hideDateTimePicker();
    };

    render() {
        const { date } = this.state;
        return (
            <View style={styles.view}>
                <Item style={styles.item}>
                    <TouchableOpacity style={styles.textView} onPress={this._showDateTimePicker}>
                        <Text >{date ? this.state.date : 'วัน/เดือน/ปีเกิด'}</Text>
                    </TouchableOpacity>
                </Item>
                <DateTimePicker
                    titleIOS='เลือก วัน/เดือน/ปีเกิด'
                    is24Hour
                    confirmTextIOS='บันทึก'
                    cancelTextIOS='ยกเลิก'
                    format="DD/MM/YYYY"
                    isVisible={this.state.isDateTimePickerVisible}
                    onConfirm={this._handleDatePicked}
                    onCancel={this._hideDateTimePicker}
                />
            </View>
        )
    }
}

const styles = StyleSheet.create({
    view: {
        marginTop: 10
    },
    item: {
        width: deviceWidth / 1.25,
        padding: 15,
        backgroundColor: '#fff',
        borderRadius: 15,
        justifyContent: 'center',
        alignSelf: 'center',
    },
    icon: {
        marginLeft: -deviceWidth / 100,
        width: deviceWidth / 13,
        height: deviceWidth / 13,
    },
    textView: {
        flex: 1,
        alignItems: 'center',
        justifyContent: 'center',
    },
    postfix: {
        marginRight: deviceWidth / 10,
    }
});