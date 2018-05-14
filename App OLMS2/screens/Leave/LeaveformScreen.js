import React, { Component } from 'react';
import {NavigationActions, } from 'react-navigation';
import { Container, Root, Header, View, Content, Button, Item, Input, Toast, Text, Form, Picker, Left, Icon, Body, Title, Right, Textarea, Spinner } from 'native-base';
import DatePicker from './DatePicker';
import GenderPicker from './GenderPicker';
import {StyleSheet, Dimensions, KeyboardAvoidingView, AsyncStorage} from 'react-native';
import axios from 'axios'
import {HOST} from '../host'
import moment from 'moment';
export default class LeaveformScreen extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
          gender: "ลากิจ",
          date: "",
          description:"",
          showToast: false,
          isLoading: false
        };
        this.setUser();
    }

    setUser = async() => {
        this.setState({
            username: await AsyncStorage.getItem('username'),
            password: await AsyncStorage.getItem('password'),
          });
    }
    
    handleGender = async (gender) => {
        await this.setState({ gender });
    }

    handleDate = async (date) => {
        await this.setState({ date });
    }

    setComment = async(event) => {
        await this.setState({description: event});
    }

    leave = async(username, password, id, format, Start, description) => {
            try {
                const result = await axios({
                    method: 'post',
                    url: HOST+'mobileLeave',
                    data: {
                        username,
                        password,
                        id,
                        format,
                        description,
                        Start,
                    }
                })
                return result.data.subjects;
              } catch (error) {
                console.error(error);
              }
    }
    submitLeave = async(username, password, id) => {
        this.setState({isLoading: await true});
        if (this.state.date == '') {
            Toast.show({
                text: 'กรุณาเพิ่มวันที่',
                buttonText: 'ตกลง',
                type: "warning"
            })
            this.setState({isLoading: false});
        } else if (this.state.description == '') {
            Toast.show({
                text: 'กรุณาเพิ่มคำอธิบาย',
                buttonText: 'ตกลง',
                type: "warning"
            })
            this.setState({isLoading: false});
        } else {
            var result = await this.leave(username, password, id, this.state.gender, this.state.date, this.state.description);

            if(!result){
                this.setState({isLoading: await false});
                Toast.show({
                    text: ' จำนวนการลาครบแล้ว',
                    buttonText: 'ตกลง',
                    type: "danger"
                })
                this.setState({isLoading: false});
            } else {
                this.setState({isLoading: await false});
                this.props.navigation.goBack();
                Toast.show({
                    text: 'ลาเรียนสำเร็จแล้ว',
                    buttonText: 'ตกลง',
                    type: "success"
                })
                this.setState({isLoading: false});
            }
        }
    }
    render() {
    const { goBack } = this.props.navigation;
    const { date, gender } = this.state;
    const isLoading = this.state.isLoading ? <Spinner color='#fff' /> : <Text>ลาเรียน</Text>;
    return (
        <Container>
            <Header >
            <Left>
                <Button transparent onPress={ () => goBack()}>
                    <Icon name='arrow-back' />
                </Button>
            </Left>
            <Body>
                <Title>{this.props.navigation.state.params.name}</Title>
            </Body>
            <Right></Right>
            </Header>
            <Content >
                <View style={styles.position}>
                <View >
                    <GenderPicker gender={gender} handle={this.handleGender} />
                </View>
                <View>
                    <DatePicker date={date} handle={this.handleDate} />
                </View>
                <Textarea style={styles.border2} onChangeText={this.setComment} placeholder="อธิบายเพิ่มเติม" />
                <View>
                    <Button block style={styles.button} onPress={ () => this.submitLeave(this.state.username, this.state.password, this.props.navigation.state.params.id)}>
                        {isLoading}
                    </Button>
                </View>
                </View>
            </Content>
        </Container>
    );
  }
}

const deviceHeight = Dimensions.get("window").height;
const deviceWidth = Dimensions.get("window").width;

const styles = StyleSheet.create({
    position: {
        marginTop: deviceHeight/4.5,
    },
    border: {
        width: deviceWidth / 1.25,
        backgroundColor: '#fff',
        borderRadius: 15,
        justifyContent: 'center',
        alignSelf: 'center',
        marginTop: 10
    },
    border2: {
        width: deviceWidth / 1.25,
        backgroundColor: '#fff',
        borderRadius: 15,
        justifyContent: 'center',
        alignSelf: 'center',
        marginTop: 10,
        padding: 10
    },
    button:{
        width: deviceWidth / 1.25,
        marginTop: 10,
        justifyContent: 'center',
        alignSelf: 'center',
    }
});