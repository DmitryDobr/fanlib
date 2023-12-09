import axios from 'axios';
import { useAuth } from './useAuth'

const AddComment = (props) => {
    let {user} = useAuth();
    var WorkId = props.WorkId

    // console.log(WorkId)

    const AddComment = (event) => {
        event.preventDefault()
        const form = event.target;

        var newcommenttext = form.commenttext.value;

        axios
        .post(`http://fanlib-api.ru/insert/comment`, null, {params: {
            user_id: user.user_id,
            work_id: WorkId,
            commenttext: newcommenttext,
        }})
        .then((response) => {
            console.log(response.data)
            if (response.data.status === true)
            {
                props.callback(WorkId);
                form.commenttext.clear();
            }
            alert(response.data.message)
        })
    }

    if (user)
    {
        return (
            <>
                <hr></hr>
                <h1>Оставить отзыв о работе</h1>
                <form className='EditForm' onSubmit={AddComment}>
                    <textarea className='EditForm-textarea' 
                    maxLength='1000' name="commenttext" id="0" 
                    cols="30" rows="10" placeholder='Введите текст'></textarea>
                    <input className='LoginForm-button' type="submit" value='Отправить' />
                </form>
            </>
        )
    }
    else
    {
        return (
            <div>
                Войдите в аккаунт чтобы добавить комментарий
            </div>
        )
    }
}

export {AddComment}