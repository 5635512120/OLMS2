import { Item as FormItem, Text, Picker, Form, Header, Left, Right, Body, Button, Icon, Title, Content } from 'native-base';
import { StyleSheet, Image, Dimensions, View, Platform } from "react-native";
import React, { Component } from 'react';

const deviceHeight = Dimensions.get("window").height;
const deviceWidth = Dimensions.get("window").width;

const Item = Picker.Item;

export default class GenderPicker extends Component {

    constructor(props) {
        super(props);
        this.state = this.props;
    }

    componentWillReceiveProps(nextProps) {
        this.setState({ gender: nextProps.gender });
    }

    render() {
        const { gender } = this.state;
        return (
            <View style={styles.view}>
                <FormItem style={styles.item}>
                    <Content>
                        <Form style={styles.form}>
                            <Picker
                                style={styles.picker}
                                iosHeader='เลือกประเภทการลา'
                                placeholder='เลือกประเภทการลา'
                                mode="dropdown"
                                selectedValue={gender ? gender : "ลากิจ"}
                                onValueChange={(value) => { this.props.handle(value) }}
                                headerBackButtonText='ย้อนกลับ'
                            >
                                <Picker.Item label="ลากิจ" value="ลากิจ" />
                                <Picker.Item label="ลาป่วย" value="ลาป่วย" />
                            </Picker>
                        </Form>
                    </Content>
                </FormItem>
            </View>
        )
    }
}

const styles = StyleSheet.create({
    view: {
        paddingTop: 30,
    },
    item: {
        marginLeft: deviceWidth / 10,
        marginRight: deviceWidth / 10,
        padding: deviceWidth / 60,
        backgroundColor: '#ffffff',
        borderRadius: 15,
    },
    icon: {
        marginLeft: deviceWidth / 100,
        width: deviceWidth / 13,
        height: deviceWidth / 13,
    },
    form: {
        alignSelf: 'center',
        marginLeft: Platform.OS == 'ios' ? undefined : -deviceWidth / 10,
    },
    text: {
        fontFamily: 'KanitLight',
    },
    picker: {
        width: Platform.OS === 'ios' ? undefined : 120,
        alignSelf: 'center',
        marginLeft: Platform.OS === 'ios' ? undefined : deviceWidth / 5
    }
});